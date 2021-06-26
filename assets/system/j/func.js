function del_item(url,id,ta_type)
{
  if(confirm('確定要刪除?'))
  {
    $.ajax({
        url: url,
        data: {type:'ajaxdel',id:id,ta_type:ta_type},
        error: function(xhr) { alert('Ajax request 發生錯誤');},
        success: function(response) {
          if(response=='ok')
          {
            alert('刪除成功');
            $("#row_"+id).fadeOut();
          }
          else
            alert('刪除失敗');
        }
    });
        return false;
  }else{
        return false;
    }
    
}

