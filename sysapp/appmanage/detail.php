<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	
	//验证是否登入
	if(!checkLogin()){
		header('Location: ../error.php?code='.$errorcode['noLogin']);
	}
	//验证是否为管理员
	else if(!checkAdmin()){
		header('Location: ../error.php?code='.$errorcode['noAdmin']);
	}
	//验证是否有权限
	else if(!checkPermissions(1)){
		header('Location: ../error.php?code='.$errorcode['noPermissions']);
	}
	
	if(isset($appid)){
		$app = $db->select(0, 1, 'tb_app', '*', 'and tbid='.$appid);
	}else{
		//给个初始值
		$app = array(
			'type' => 'app',
			'isresize' => 1,
			'isopenmax' => 0,
			'isflash' => 0
		);
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>应用管理</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<form action="detail.ajax.php" method="post" name="form" id="form">
<input type="hidden" name="ac" value="edit">
<input type="hidden" name="id" value="<?php echo $appid; ?>">
<div class="creatbox">
	<div class="middle">
		<p class="detile-title">编辑应用</p>
		<div class="input-label">
			<label class="label-text">应用图标：</label>
			<div class="label-box">
				<div class="shortcutbox">
					<?php if($app['icon'] != NULL){ ?>
						<div class="shortcut-addicon bgnone"><input type="file" id="uploadfilebtn" style="position:absolute;right:0;bottom:0;opacity:0;filter:alpha(opacity=0);display:block;width:200px;height:100px"><img src="../../<?php echo $app['icon']; ?>"></div>
					<?php }else{ ?>
						<div class="shortcut-addicon"><input type="file" id="uploadfilebtn" style="position:absolute;right:0;bottom:0;opacity:0;filter:alpha(opacity=0);display:block;width:200px;height:100px"></div>
					<?php } ?>
					<div class="shortcut-selicon">
						<a href="javascript:;"><img src="../../img/ui/system-gear.png" valsrc="img/ui/system-gear.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-users.png" valsrc="img/ui/system-users.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-wrench.png" valsrc="img/ui/system-wrench.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-star.png" valsrc="img/ui/system-star.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-shapes.png" valsrc="img/ui/system-shapes.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-chart-bar.png" valsrc="img/ui/system-chart-bar.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-document-edit.png" valsrc="img/ui/system-document-edit.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-documents.png" valsrc="img/ui/system-documents.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-mail.png" valsrc="img/ui/system-mail.png"></a>
						<a href="javascript:;"><img src="../../img/ui/system-puzzle.png" valsrc="img/ui/system-puzzle.png"></a>
					</div>
				</div>
				<input type="hidden" name="val_icon" id="val_icon" value="<?php echo $app['icon']; ?>">
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用名称：</label>
			<div class="label-box">
				<input type="text" class="text" name="val_name" value="<?php echo $app['name']; ?>">
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用分类：</label>
			<div class="label-box">
				<select name="val_kindid">
					<?php
						foreach($apptype as $at){
							if($at['id'] == $app['kindid']){
								echo '<option value="'.$at['id'].'" selected>'.$at['name'].'</option>';
							}else{
								echo '<option value="'.$at['id'].'">'.$at['name'].'</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用地址：</label>
			<div class="label-box">
				<input type="text" name="val_url" value="<?php echo $app['url']; ?>" style="width:300px">
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">窗口大小：</label>
			<div class="label-box">
				<div class="input-prepend input-append fl">
					<span class="add-on">宽</span><input type="text" name="val_width" value="<?php echo $app['width']; ?>" style="width:40px"><span class="add-on">px</span>
				</div>
				<div class="input-prepend input-append fl" style="margin-left:20px">
					<span class="add-on">高</span><input type="text" name="val_height" value="<?php echo $app['height']; ?>" style="width:40px"><span class="add-on">px</span>
				</div>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用类型：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_type" value="app" <?php if($app['type'] == 'app'){echo 'checked';} ?> <?php if(isset($appid)){echo 'disabled';} ?>>APP</label>
				<label class="radio"><input type="radio" name="val_type" value="widget" <?php if($app['type'] == 'widget'){echo 'checked';} ?> <?php if(isset($appid)){echo 'disabled';} ?>>挂件</label>
			</div>
		</div>
		<div class="input-label input-label-isresize" <?php if($app['type'] == 'widget'){echo 'style="display:none"';} ?>>
			<label class="label-text">窗口是否拉伸：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isresize" value="1" <?php if($app['isresize'] == 1){echo 'checked';} ?>>是</label>
				<label class="radio"><input type="radio" name="val_isresize" value="0" <?php if($app['isresize'] == 0){echo 'checked';} ?>>否</label>
			</div>
		</div>
		<div class="input-label input-label-isopenmax" <?php if($app['type'] == 'widget' && $app['isresize'] == 0){echo 'style="display:none"';} ?>>
			<label class="label-text">打开默认最大化：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isopenmax" value="1" <?php if($app['isopenmax'] == 1){echo 'checked';} ?>>是</label>
				<label class="radio"><input type="radio" name="val_isopenmax" value="0" <?php if($app['isopenmax'] == 0){echo 'checked';} ?>>否</label>
			</div>
		</div>
		<div class="input-label input-label-isflash" <?php if($app['type'] == 'widget'){echo 'style="display:none"';} ?>>
			<label class="label-text">是否为Flash：</label>
			<div class="label-box form-inline">
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isflash" value="1" <?php if($app['isflash'] == 1){echo 'checked';} ?>>是</label>
				<label class="radio" style="margin-right:10px"><input type="radio" name="val_isflash" value="0" <?php if($app['isflash'] == 0){echo 'checked';} ?>>否</label>
				<span class="txt">[<a href="javascript:;" rel="tooltip" title="Flash应用可能会导致窗口覆盖，请认真选择">?</a>]</span>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">应用介绍：</label>
			<div class="label-box">
				<textarea class="textarea" name="val_remark" id="val_remark" style="width:300px;height:100px;margin-bottom:10px;"><?php echo $app['remark']; ?></textarea>
			</div>
		</div>
	</div>
</div>
<div class="bottom-bar">
	<div class="con">
		<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
		<a class="btn btn-large" menu="back" href="index.php"><i class="icon-arrow-left"></i> 返回应用列表</a>
	</div>
</div>
</form>
<?php include('sysapp/global_js.php'); ?>
<script>
$().ready(function(){
	//初始化ajaxForm
	var options = {
		beforeSubmit : showRequest,
		success : showResponse,
		type : 'POST'
	};
	$('#form').ajaxForm(options);
	$('input[name="val_type"]').change(function(){
		if($(this).val() == 'app'){
			$('.input-label-isresize, .input-label-isopenmax, .input-label-isflash').slideDown();
		}else{
			$('input[name="val_isresize"]').each(function(){
				if($(this).val() == '1'){
					$(this).attr('checked', true);
				}
			});
			$('input[name="val_isopenmax"]').each(function(){
				if($(this).val() == '0'){
					$(this).attr('checked', true);
				}
			});
			$('input[name="val_isflash"]').each(function(){
				if($(this).val() == '0'){
					$(this).attr('checked', true);
				}
			});
			$('.input-label-isresize, .input-label-isopenmax, .input-label-isflash').slideUp();
		}
	});
	$('input[name="val_isresize"]').change(function(){
		if($(this).val() == '1'){
			$('.input-label-isopenmax').slideDown();
		}else{
			$('.input-label-isopenmax').slideUp();
		}
	});
	//选择图标
	$('.shortcut-selicon a').click(function(){
		$('.shortcut-addicon img').remove();
		$('.shortcut-addicon').addClass('bgnone').append($(this).html());
		$('#val_icon').val($(this).children('img').attr('valsrc'));
	});
	//提交
	$('a[menu=submit]').click(function(){
		$('#form').submit();
	});
	$('#uploadfilebtn').on('change', function(e){
		var files = e.target.files || e.dataTransfer.files;
		if(files.length == 0){
			return;
		}
		//检测文件是不是图片
		if(files[0].type.indexOf('image') === -1){
			alert('请上传图片');
			return false;
		}
		//检测文件大小是否超过1M
		if(files[0].size > 1024*1024){
			alert('图片大小超过1M');
			return;
		}
		var fd = new FormData();
		fd.append('xfile', files[0]);
		var xhr = new XMLHttpRequest();
		if(xhr.upload){
			$.dialog({
				id: 'uploadImg',
				title: '正在上传',
				content: '<div id="imgProgress" class="progress progress-striped active" style="width:200px;margin-bottom:0"><div class="bar"></div></div>',
				cancel: false
			});
			xhr.upload.addEventListener('progress', function(e){
				if(e.lengthComputable){
					var loaded = Math.ceil(e.loaded / e.total * 100);
					$('#imgProgress .bar').css({
						width: loaded + '%'
					});
				}
			}, false);
			xhr.addEventListener('load', function(e){
				$('#uploadfilebtn').val('');
				$.dialog.list['uploadImg'].close();
				if(xhr.readyState == 4 && xhr.status == 200){
					var result = jQuery.parseJSON(e.target.responseText);
					if(result.state == 'SUCCESS'){
						$('.shortcut-addicon img').remove();
						$('.shortcut-addicon').addClass('bgnone').append('<img src="../../' + result.url + '" />');
						$('#val_icon').val(result.url);
					}else{
						ZENG.msgbox.show(result.state, 5, 2000);
					}
				}
			}, false);
			xhr.open('post', 'detail.ajax.php?ac=uploadImg', true);
			xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
			xhr.send(fd);
		}
	});
});
function showRequest(formData, jqForm, options){
	//alert('About to submit: \n\n' + $.param(formData));
	return true;
}
function showResponse(responseText, statusText, xhr, $form){
	//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
	if($('input[name="value_1"]').val() != ''){
		if(responseText == ''){
			$.dialog({
				id : 'ajaxedit',
				content : '修改成功',
				ok : function(){
					$.dialog.list['ajaxedit'].close();
					window.parent.HROS.app.get();
				}
			});
		}
	}else{
		if(responseText == ''){
			$.dialog({
				id : 'ajaxedit',
				content : '添加成功',
				ok : function(){
					$.dialog.list['ajaxedit'].close();
					window.parent.HROS.app.get();
				}
			});
		}
	}
}
</script>
</body>
</html>