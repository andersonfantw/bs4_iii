<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.b_id}><{if $val.b_top==1}><br /><span class="new">[NEW]</span><{/if}>
								</td>
								<td>
									<img src="<{$host_base}>/uploadfiles/s_<{$val.filename}>" />
								</td>
								<td>
<{if $val.b_status=='1'}>
	 <{$val.b_name}>
<{else}>
	<span class="off"><{$val.b_name}></span>
<{/if}>
<br /><br />
<{if $val.writer_data!='' || $val.cowriter_data!=''}>
作者: <{$val.writer_data}><br />
共同作者: <{$val.cowriter_data}><br />
<br /><br />
<{/if}>

KEY: <{$val.b_key}><br />
EcocatID: <{$val.ecocat_id}>
								</td>
                <td>
                  <{$val.b_order}>
                </td>
<!--
                <td>
                  <{if $val.b_top==1}>新書<{/if}>
                </td>

								<td>
									<{if $val.b_status=='on'}>上架中<{else}>下架中<{/if}>
								</td>
-->
                <td>
                  <{$val.b_views_webbook}>
                </td>
                <td>
                  <{$val.b_views_ibook}>
                </td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_EDIT}>" href="book.php?type=edit&id=<{$val.b_id}>&page=<{$smarty.get.page}>">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BTNHINT_DELETE}>" href="book.php?type=delete&id=<{$val.b_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
                  <{if $smarty.const.MEMBER }>
                  <a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_READINGTIME}>" href="book_readingtime.php?id=<{$val.b_id}>&page=<{$smarty.get.page}>">
                    <span class="ui-icon ui-icon-clock"></span>
                  </a>
									<{/if}>
									<{if $smarty.const.MEMBER && $smarty.const.CONFIG_MYBOOKSHELF}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_MYBOOKSHELF}>" href="book.php?type=users_bookshelf&id=<{$val.b_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-note"></span>
									</a>
									<{/if}>
<{*
                  <{if $val.ecocat_id}>
                  <a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_RELOADIMAGE}>" href="ecocat_update_image.php?id=<{$val.b_id}>&page=<{$smarty.get.page}>">
                    <span class="ui-icon ui-icon-refresh"></span>
                  </a>
                  <{/if}>
									<{if ($game_data[$val.b_id] && $smarty.const.REFLECTION_GAME)}>
                  <a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="" href="game.php?id=<{$val.b_id}>&page=<{$smarty.get.page}>">
                    <span class="ui-icon ui-icon-comment"></span>
                  </a>
                  <{/if}>
*}>
								</td>
							</tr>
						<{/foreach}>
