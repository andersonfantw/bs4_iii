<?php
class page{
 var $perpage;   //分頁筆數
 var $page;      //目前頁數
 var $records;   //總筆數
 var $pages;     //總頁數
 var $url;       //連結網址
 var $pageBar;   //導覽列
 var $noLink='javascript:void(0)';

 var $max_link=5;//1 3 5 7 9 11....
 
 function page($perpage,$page,$records,$url){
  $this->perpage=$perpage;
  $this->page=$page;
  $this->records=$records;
  $this->pages=ceil($records/$perpage);
  $this->url=(strpos($url,'?'))?$url.'&':$url.'?';
 }
 function showPageBar(){  
  $this->pageBar.="<ul class='pagination'>";
  /*$this->pageBar.="<li class='pagebar'><a href='";
  $this->pageBar.=($this->page==1)?$this->noLink:$this->url.'page=1';
  $this->pageBar.="'>第一頁</a></li>";*/
  //-----------------------------------------------------------
  //$this->pageBar.="<ul class='pagebar'>";
  //-----------------------------------------------------------
  if($this->page>1){
   $this->pageBar.="<li class='previous'><a href='";
   $this->pageBar.=$this->url.'page='.($this->page-1);
   $this->pageBar.="'>上一頁</a></li>";
  }else{
   //$this->pageBar.="<li class='previous-off'>上一頁</li>";
  }
  //-----------------------------------------------------------

	if($this->page<$this->max_link)
	{
		$perv_link=$this->page-1;
		$next_link=$this->max_link-$this->page;
	}else if($this->page > ($this->pages-$this->max_link))
	{
		$next_link=$this->pages-$this->page;
		$perv_link=$this->max_link-$next_link;
	}else
	{
		$perv_link=$this->max_link-intval($this->max_link*0.5);
		$next_link=$this->max_link-$perv_link;
	}
  
/*  
  $start=($this->page>$perv_link)?$this->page-$perv_link:1;
  $end=(($this->page+$next_link)>$this->pages)?$this->pages:$this->page+$next_link;
 */
  $start=($this->page>5)?$this->page-5:1;
  $end=(($this->page+5)>$this->pages)?$this->pages:$this->page+5;
  if($start<6)
  {
    $end = $start+10;
    $end = $end>$this->pages?$this->pages:$end;
  }
  if($this->pages-$this->page<5)
  {
    $start = $end-10;
    $start = $start<1?1:$start;
  }
  for($i=$start;$i<=$end;$i++){
   if($i!=$this->page){
    $this->pageBar.="<li><a href='";
    $this->pageBar.=$this->url.'page='.$i;
    $this->pageBar.="'>$i</a></li>";
   }else{
    $this->pageBar.="<li class='active'>$i</li>";
   }
   }
  //-----------------------------------------------------------
  if($this->page<$this->pages){
   $this->pageBar.="<li class='pagebar'><a class='btn_next' href='";
   $this->pageBar.=$this->url.'page='.($this->page+1);
   $this->pageBar.="'>下一頁</a></li>";
  }else{
   //$this->pageBar.="<li class='next-off'>下一頁</li>";
  }
  //-----------------------------------------------------------
  //$this->pageBar.="</ul>";
  //-----------------------------------------------------------
  /*$this->pageBar.="<li class='pagebar'><a href='";
  $this->pageBar.=($this->page<$this->pages)?$this->url.'page='.$this->pages:$this->noLink;
  $this->pageBar.="'>最終頁</a></li>";*/
  $this->pageBar.="</ul>";
  /*$this->pageBar.="</td></tr></table>";*/
  return $this->pageBar;
 }
}
?>
