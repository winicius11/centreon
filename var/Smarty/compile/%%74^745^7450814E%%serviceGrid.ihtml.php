<?php /* Smarty version 2.6.18, created on 2014-01-06 15:58:05
         compiled from serviceGrid.ihtml */ ?>
<script type="text/javascript" src="./include/common/javascript/tool.js"></script>
<form name='form'>
<input name="p" value="<?php echo $this->_tpl_vars['p']; ?>
" type="hidden">
<input name="o" value="svc" type="hidden">
<table class="ajaxOption">
	<tr>
		<td><?php echo $this->_tpl_vars['search']; ?>
</td>
		<td><input size="15" id="host_search" class="search_input" style="padding-top:1px;padding-bottom:1px;" name="host_search" type="text" /></td>
		<?php if ($this->_tpl_vars['poller_listing']): ?>
		<td><?php echo $this->_tpl_vars['pollerStr']; ?>
:</td>
		<?php endif; ?>
		<td><span id="instance_selected"></span></td>
	    <td><?php echo $this->_tpl_vars['hgStr']; ?>
:</td>
	    <td><span id="hostgroups_selected"></span></td>
	</tr>
</table>
<br/>
<table class="ToolbarTable">
	<tr class="ToolbarTR">
		<td class="Toolbar_TDSelectAction_Top" width="460">
			<span class="consol_button"><a id="JS_monitoring_refresh" href="#" onclick="javascript:monitoring_refresh('');"><img src='./img/icones/16x16/refresh.gif' alt='Refresh' title='Refresh'></a></span>
			<span class="consol_button"><a id="JS_monitoring_play" class="cachediv" href="#" onclick="javascript:monitoring_play('');"><img src='./img/icones/16x16/media_play.gif' alt='Play' title='Play'></a></span>
			<span class="consol_button"><img id="JS_monitoring_play_gray"  src='./img/icones/16x16/media_play_gray.gif' alt='Play' title='Play'></span>
			<span class="consol_button"><a class="" id="JS_monitoring_pause" href="#" onclick="javascript:monitoring_pause('');"><img src='./img/icones/16x16/media_pause.gif' alt='Pause' title='Pause'></a></span>
			<span class="consol_button"><img id="JS_monitoring_pause_gray" class="cachediv" src='./img/icones/16x16/media_pause_gray.gif' alt='Pause' title='Pause'></span>
			<input name="p" value="<?php echo $this->_tpl_vars['p']; ?>
" type="hidden">
		</td>
		<td id="pagination1" class="ToolbarPagination"></td>
		<td id="sel1" class="Toolbar_pagelimit"></td>
	</tr>
</table>
<div id="forAjax"></div>
<table class="ToolbarTable">
	<tr>
		<td class="Toolbar_TDSelectAction_Bottom" width="300"><input name="p" value="<?php echo $this->_tpl_vars['p']; ?>
" type="hidden"></td>
		<td id="pagination2" class="ToolbarPagination"></td>
		<td id="sel2" class="Toolbar_pagelimit"></td>
	</tr>
</table>
<?php if ($this->_tpl_vars['host_name']): ?>
<input type='hidden' name='host_name' value='<?php echo $this->_tpl_vars['host_name']; ?>
'>
<?php endif; ?>
<input name='cmd' id='cmd' value='42' type='hidden'>
<input name='o' value='<?php echo $this->_tpl_vars['o']; ?>
' type='hidden'>
<input type='hidden' id='limit' name='limit' value='<?php echo $this->_tpl_vars['limit']; ?>
'>	
<?php echo $this->_tpl_vars['form']['hidden']; ?>

</form>