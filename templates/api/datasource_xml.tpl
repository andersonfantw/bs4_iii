<?xml version="1.0" encoding="UTF-8"?>
<book>
<{foreach from=$data item="val" name=myloop}>
<detail No="<{$smarty.foreach.myloop.iteration}>">
  <process_id><{$bs_unique_id}>_<{$val.b_id}></process_id>
  <book_name><{$val.b_name}></book_name>
  <image_url><{$val.f_path}></image_url>
  <book_url><{$val.webbook_link}></book_url>
  <pdf_path><{$val.ibook_link}></pdf_path>
</detail>
<{/foreach}>
</book>