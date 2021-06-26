/*
fts_status: 1:one way, 2:two way, 3:mutual
fts_content: format (,val1,val2,val3,)
*/
create table bookshelf2_fulltext_synonyms(
	fts_id  serial(1),
	fts_status  smallint default 0 ,
	fts_name  varchar(50) not null  ,
	fts_content  long varchar not null  ,
	createdate  timestamp default null)
 in deftablespace  lock mode row  fillfactor 100 ;
alter table bookshelf2_fulltext_synonyms primary key (fts_id) in deftablespace;

/*碩網*/
CREATE VIEW BOOKSHELF2_VIEW_BOOKS_WEBBOOKLINK as
select
	b_id,b_key,b_type,substring(webbook_link,17,100) as webbook_link
from bookshelf2_books
where b_type in(8) and bs_id>=6
union 
select 
	b_id,b_key,b_type,replace(replace(webbook_link,'?','%3f'),'=','%3d') as webbook_link
from bookshelf2_books
where b_type in (2) and bs_id>=6

/*碩網*/
CREATE VIEW BOOKSHELF2_VIEW_FULLTEXT_BOOKS as
select
	t.b_id,b_key,b_name,b_description, b_status as enable, create_date, edit_date, open_link, pdf, txt
from(
	select b_id,
		webbook_link as open_link,
		concat(concat('/files/',b_key),'.pdf') as pdf,
		replace(webbook_link,'book.php','data/fulltextsearch.txt') as txt
	from BOOKSHELF2_VIEW_BOOKS_WEBBOOKLINK
	where b_type in(8)
	union
	select b_id,
		concat('/api/redirect.php?u=',webbook_link) as open_link,
		'' as pdf,
		concat('/plugin/fulltextsearch/api/youtube.php?u=',webbook_link) as txt
	from BOOKSHELF2_VIEW_BOOKS_WEBBOOKLINK
	where b_type in(2)
)as t
left join bookshelf2_books b on(t.b_id=b.b_id);

--remove
CREATE VIEW BOOKSHELF2_VIEW_FULLTEXT_BOOKS as
select
	b_id,b_key,b_type,b_name,b_description, b_status as enable, create_date, edit_date,
	substring(webbook_link,17,100) as open_link,
	concat(concat('/files/',b_key),'.pdf') as pdf,
	replace(substring(webbook_link,17,100),'book.php','data/fulltextsearch.txt') as txt
from bookshelf2_books
where ibook_link <> '' and b_type in(8,9,10)
union
select 
	b_id,b_key,b_name,b_description, enable, create_date, edit_date,
	concat('/api/redirect.php?u=',webbook_link) as open_link,
	'' as pdf,
	concat('/api/redirect.php?u=',webbook_link) as txt
from(
	select 
	b_id,b_key,b_name,b_description, b_status as enable, create_date, edit_date,
	replace(replace(webbook_link,'?','%3f'),'=','%3d') as webbook_link
	from bookshelf2_books
	where b_type in (2)
) as t
		
/*碩網*/
CREATE VIEW BOOKSHELF2_VIEW_FULLTEXT_BOOKTAG_REF as
select bt.b_id,tk.tk_name as key,tv.tv_name as name, bt.createdate
from bookshelf2_book_tag bt
left join bookshelf2_tag t on(bt.t_id=t.t_id)
left join bookshelf2_tagval tv on(t.tv_id=tv.tv_id)
left join bookshelf2_tagkey tk on(t.tk_id=tk.tk_id)

/*碩網*/
CREATE VIEW BOOKSHELF2_VIEW_FULLTEXT_DELETED_BOOKS as
select
	b_id,b_key,b_name, create_date
from bookshelf2_books
where ibook_link <> '' and b_type in(8,9,10) and b_status=0


/*國尊*/
create table BOOKSHELF2_TEXTINDEXQ (
 B_ID integer default null ,    //書本ID
 B_KEY  VARCHAR(50) default null ,    //書本key
 EXECTIME  TIMESTAMP default now() ,   //新增編輯刪除時間
 TEXTCOLM  INTEGER default 1 ,     //1: 全文檢索的內容有異動, 0: 無異動
 NONTEXTCOLM  INTEGER default 0 ,     //1: 非全文檢索的欄位有異動, 0: 無異動
 DELETED  INTEGER default 0 )        //1: 刪除


刪除: 
CREATE TRIGGER trgTiDel AFTER UPDATE OF B_STATUS on BOOKSHELF2_BOOKS FOR EACH ROW when (new.B_STATUS=0) (insert into BOOKSHELF2_TEXTINDEXQ (B_ID,B_KEY,ExecTime,TextColM,NonTextColM,Deleted) values (new.B_ID,new.B_KEY,now(),0,0,1));

新增:
CREATE TRIGGER trgTiIns AFTER INSERT ON BOOKSHELF2_BOOKS FOR EACH ROW (insert into BOOKSHELF2_TEXTINDEXQ (B_ID,B_KEY,ExecTime,TextColM,NonTextColM) values (new.B_ID,new.B_KEY,now(),1,1));

異動:
CREATE TRIGGER trgTiUpdText AFTER UPDATE OF EDIT_DATE on BOOKSHELF2_BOOKS FOR EACH ROW (insert into BOOKSHELF2_TEXTINDEXQ (B_ID,B_KEY,ExecTime,TextColM,NonTextColM) values (new.B_ID,new.B_KEY,now(),1,0));

CREATE VIEW BOOKSHELF2_VIEW_FULLTEXT_BOOKS as
select ab.u_id as uid,b.bs_id as bsid, b.b_key as bkey, b.b_id as bid, b.b_name as bname
from BOOKSHELF2_TEXTINDEXQ ti
join bookshelf2_books b on (b.b_id=ti.b_id)
left join BOOKSHELF2_ACCOUNT_BOOKSHELF ab on(ab.bs_id=b.bs_id)

CREATE VIEW BOOKSHELF2_VIEW_FULLTEXT_BOOKTAG_REF as
select
	b.b_id as bid,b.b_key as bkey,b.b_name as bname,
	tk.tk_name as key, tv.tv_name as val,
	tk1.tk_name as pkey, tv1.tv_name as pval
from bookshelf2_books b 
left join bookshelf2_book_tag bt on(b.b_id=bt.b_id)
left join BOOKSHELF2_TAG t1 on(t1.t_id=bt.t_id)
left join BOOKSHELF2_TAGKEY tk on(t1.tk_id=tk.tk_id)
left join BOOKSHELF2_TAGVAL tv on(t1.tv_id=tv.tv_id)
left join BOOKSHELF2_TAG t2 on(t1.t_parent_id=t2.t_id)
left join BOOKSHELF2_TAGKEY tk1 on(t2.tk_id=tk1.tk_id)
left join BOOKSHELF2_TAGVAL tv1 on(t2.tv_id=tv1.tv_id)