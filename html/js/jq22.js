 /* www.jq22.com */
$(function(){
	// 初始化插件
	$("#upload").zyUpload({
		width            :   "100%",                 // 宽度
		height           :   "auto",                 // 宽度
		itemWidth        :   "120px",                 // 文件项的宽度
		itemHeight       :   "100px",                 // 文件项的高度
		url              :   "/upload/UploadAction",  // 上傳文件的路径
		multiple         :   true,                    // 是否可以多个文件上傳
		dragDrop         :   false,                    // 是否可以拖动上傳文件
		del              :   true,                    // 是否可以删除文件
		finishDel        :   false,  				  // 是否在上傳文件完成后删除预览
		/* 外部获得的回调接口 */
		onSelect: function(files, allFiles){                    // 選擇文件的回调方法
			console.info("当前選擇了以下文件：");
			console.info(files);
			console.info("之前没上傳的文件：");
			console.info(allFiles);
		},
		onDelete: function(file, surplusFiles){                     // 删除一个文件的回调方法
			console.info("当前删除了此文件：");
			console.info(file);
			console.info("当前剩余的文件：");
			console.info(surplusFiles);
		},
		onSuccess: function(file){                    // 文件上傳成功的回调方法
			console.info("此文件上傳成功：");
			console.info(file);
		},
		onFailure: function(file){                    // 文件上傳失败的回调方法
			console.info("此文件上傳失败：");
			console.info(file);
		},
		onComplete: function(responseInfo){           // 上傳完成的回调方法
			console.info("文件上傳完成");
			console.info(responseInfo);
		}
	});
});

