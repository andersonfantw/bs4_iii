create table bookshelf2_quicksearch(
	q_id  serial(1),
	bu_id  INTEGER not null,
	q_name  varchar(50) not null  ,
	q_shortname  varchar(10) not null  ,
	q_content  long varchar not null  ,
	createdate  timestamp default null)
 in deftablespace  lock mode row  fillfactor 100 ;
alter table bookshelf2_quicksearch primary key (sq_id) in deftablespace;

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

create table BOOKSHELF2_TextIndexQ(
	b_id integer,
	b_key varchar(50),
	ExecTime Timestamp default now(),
	TextColM integer default 1,
	NonTextColM integer default 0,
	Deleted integer default 0)
in DEFTABLESPACE  lock mode row  fillfactor 100 ;

CREATE TRIGGER trgTiDel AFTER UPDATE OF B_STATUS on BOOKSHELF2_BOOKS FOR EACH ROW when (new.B_STATUS=0) (insert into BOOKSHELF2_TEXTINDEXQ (B_ID,B_KEY,ExecTime,TextColM,NonTextColM,Deleted) values (new.B_ID,new.B_KEY,now(),0,0,1));
CREATE TRIGGER trgTiIns AFTER INSERT ON BOOKSHELF2_BOOKS FOR EACH ROW (insert into BOOKSHELF2_TEXTINDEXQ (B_ID,B_KEY,ExecTime,TextColM,NonTextColM) values (new.B_ID,new.B_KEY,now(),1,1));
CREATE TRIGGER trgTiUpdText AFTER UPDATE OF EDIT_DATE on BOOKSHELF2_BOOKS FOR EACH ROW (insert into BOOKSHELF2_TEXTINDEXQ (B_ID,B_KEY,ExecTime,TextColM,NonTextColM) values (new.B_ID,new.B_KEY,now(),1,0));