/* global tus */
/* eslint-disable no-console, no-alert */

let upload          = null
let uploadIsRunning = false
const toggleBtn       = document.querySelector('#toggle-btn')
const input           = document.querySelector('#file')
const progress        = document.querySelector('.progress-bar')
const progressBar     = progress.querySelector('.bar')
const alertBox        = document.querySelector('#support-alert')
const uploadList      = document.querySelector('#upload-list')
const chunkInput      = "104857600";
const parallelInput   = "2";
const endpointInput   = "http://183.240.209.145:9090/files/"

function reset () {
  input.value = ''
  toggleBtn.textContent = 'start upload'
  upload = null
  uploadIsRunning = false
}

function askToResumeUpload (previousUploads, currentUpload) {
  if (previousUploads.length === 0) return

  let text = 'You tried to upload this file previously at these times:\n\n'
  previousUploads.forEach((previousUpload, index) => {
    text += `[${index}] ${previousUpload.creationTime}\n`
  })
  text += '\nEnter the corresponding number to resume an upload or press Cancel to start a new upload'

  const answer = prompt(text)
  const index = parseInt(answer, 10)

  if (!Number.isNaN(index) && previousUploads[index]) {
    currentUpload.resumeFromPreviousUpload(previousUploads[index])
  }
}


function  startUpload (){
	const file = input.files[0]
	
	var file_metadata="";
	if(file.type.indexOf("image")>-1){
		var myimg = URL.createObjectURL(file);
		var img = new Image();
		img.src = myimg;
		img.onload = function(){
			file_metadata=img.width+" X "+img.height;
			$("#img_spec").val(file_metadata);
			comPrevw()
		}
	}else{
		fileCid();	
	}
	
	
	
  
	
}


function fileCid(){
	
	const file = input.files[0]
	let reader = new FileReader();
	reader.readAsArrayBuffer(file);
	$(".upload-progress").show();
	reader.onload = function() {
	  const data = new Uint8Array(reader.result);
	  Counter.gencid(data).then(function(cid){
		  console.log(cid);
		  //CID检索
		  $.ajax({
				  url: "/user/file_cid",
				  data:{path_id:path_id,names:file.name,cid:cid,ajax:1},
				  dataType: "json",
				  type: "post",
				  error: function(xhr, ajaxOptions, thrownError) {
					  //console.log(xhr.responseText);
					  var redata=JSON.parse(xhr.responseText);
					  layer.msg(redata['error']['message'])
					  //xhr.responseText			
		  // 					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				  },
				  beforeSend: function() {
				  },
				  complete: function() {
					  
				  },
				  success: function(data) {
					  if(data.status==1){
						  progressBar.style.width = '100%';
						  $(".upload-progress").hide();
						  layer.msg(data.info);
						  setTimeout("fileload()",500);
					  }else if(data.status==2){
						  fileUpload()
					  }else{
						  layer.msg(data.info);
					  }
				  }
		  });
		  
	  })
	};
	reader.onerror = function() {
	  console.log(reader.error);
	};
}

function fileUpload() {
  const file = input.files[0]
  // Only continue if a file has actually been selected.
  // IE will trigger a change event even if we reset the input element
  // using reset() and we do not want to blow up later.
  if (!file) {
    return
  }
  
  
  const endpoint = endpointInput;
  let chunkSize = parseInt(chunkInput, 10)
  if (Number.isNaN(chunkSize)) {
    chunkSize = Infinity
  }

  let parallelUploads = parseInt(parallelInput, 10)
  if (Number.isNaN(parallelUploads)) {
    parallelUploads = 1
  }

  toggleBtn.textContent = '暂停上传'

  const options = {
    endpoint,
    chunkSize,
    retryDelays: [0, 1000, 3000, 5000],
    parallelUploads,
    metadata   : {
      filename: file.name,
      filetype: file.type,
    },
    onError (error) {
      if (error.originalRequest) {
        if (window.confirm(`Failed because: ${error}\nDo you want to retry?`)) {
          upload.start()
          uploadIsRunning = true
          return
        }
      } else {
        window.alert(`Failed because: ${error}`)
      }

      reset()
    },
    onProgress (bytesUploaded, bytesTotal) {
      const percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2)
      progressBar.style.width = `${percentage}%`
      console.log(bytesUploaded, bytesTotal, `${percentage}%`)
    },
    onSuccess() {
      /*const anchor = document.createElement('a')
      anchor.textContent = `Download ${upload.file.name} (${upload.file.size} bytes)`
      anchor.href = upload.url
      anchor.className = 'btn btn-success'
      uploadList.appendChild(anchor)*/
	  console.log(upload);
	  var type=upload.file.name.split(".");
	  var cid=upload.url.replace(endpointInput,"");
	  $.ajax({
			  url: "/user/file_upload",
			  data:{path_id:path_id,names:upload.file.name,type:type[type.length-1],icon:$("#icon").val(),img_spec:$("#img_spec").val(),cid:cid,ajax:1},
			  dataType: "json",
			  type: "post",
			  error: function(xhr, ajaxOptions, thrownError) {
				  //console.log(xhr.responseText);
				  var redata=JSON.parse(xhr.responseText);
				  layer.msg(redata['error']['message'])
				  //xhr.responseText			
// 					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			  },
			  beforeSend: function() {
			  },
			  complete: function() {
				  $(".upload-progress").hide();
			  },
			  success: function(data) {
				  if(data.status){
					  layer.msg(data.info);
					  setTimeout("fileload()",500);
				  }else{
					  layer.msg(data.info);
				  }
			  }
	  });

      reset()
    },
  }

  upload = new tus.Upload(file, options)
  upload.findPreviousUploads().then((previousUploads) => {
    //askToResumeUpload(previousUploads, upload)
	$(".upload-progress").show();
    upload.start()
    uploadIsRunning = true
  })
}

if (!tus.isSupported) {
  alertBox.classList.remove('hidden')
}

if (!toggleBtn) {
  throw new Error('Toggle button not found on this page. Aborting upload-demo. ')
}

toggleBtn.addEventListener('click', (e) => {
  e.preventDefault()

  if (upload) {
    if (uploadIsRunning) {
      upload.abort()
      toggleBtn.textContent = '继续上传'
      uploadIsRunning = false
    } else {
      upload.start()
      toggleBtn.textContent = '暂停上传'
      uploadIsRunning = true
    }
  } else if (input.files.length > 0) {
    fileUpload()
  } else {
    input.click()
  }
})



function baseSrc2Blob(img64Str) { //处理图片base64字符，然后调用转换为二进制函数并返回文件
    var block = img64Str.split(";"); // Split the base64 string in data and contentType
    var contentType = block[0].split(":")[1];// In this case "image/gif" //Get the content type
    var realData = block[1].split(",")[1];// In this case "iVBORw0KGg...." //get the real base64 content of the file
    var blob_file = b64toBlob(realData,contentType);// Convert to blob //转成二级制原始文件内容
    return blob_file;
}
function b64toBlob(b64Data, contentType, sliceSize) { //base64转成二进制对象函数
	//来源文档：https://ourcodeworld.com/articles/read/322/how-to-convert-a-base64-image-into-a-image-file-and-upload-it-with-an-asynchronous-form-using-jquery
    contentType = contentType || '';
    sliceSize = sliceSize || 512;
    var byteCharacters = atob(b64Data);
    var byteArrays = [];
    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);
        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }
        var byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }
    var blob = new Blob(byteArrays, {type: contentType});
    return blob;
}

//////////////////////////////////////////////////////////////
// 名称：压缩图片为base64字符函数
// 使用方法：
// <input type="file" name="pic" onchange="comPrevw(this);">
// <img id="pic-v">
//////////////////////////////////////////////////////////////
function comPrevw() {
	var file = input.files[0];
	console.log(file);
	if(!/image\/\w+/.test(file.type)){
        layer.msg("只能选择图片文件！");
        return false;
    }
    var quality = 0.9;	//定义默认图片压缩后的质量（0~1）
	if (file.type=="image/gif") { quality = 1;} //gif只保存第一张图片，所以不压缩
	var reader = new FileReader();
	reader.onload = function (e) {
		var base64IMG = reader.result;
		img = new Image();
		img.onload = function () {
			
			
			
			var oWidth = 400;
			var oHeight = 400;
			
			if(img.width>400){
				oWidth = 400;
				oHeight=parseInt(img.height*(400/img.width));
			}
			
			if(oHeight>400){
				oHeight = 400;
				oWidth=parseInt(img.width*(400/img.height));
			}
			var Size = calcWH(oWidth, oHeight); //调整为合适的尺寸
			//开始进行转换到canvas再压缩操作
			var canvas = document.createElement("canvas");
			canvas.width = Size.width;	//设置画布的宽度
      		canvas.height = Size.height;//设置画布的高度
      		var ctx = canvas.getContext("2d");
      		//ctx.drawImage(图像对象,画点起始Y,画点起始Y,画出宽度,画出高度)//画出宽度和高度决定了你复刻了多少像素，和是画布宽高度是两回事
      		ctx.drawImage(img,0,0,Size.width,Size.height);
      		//此时我们可以使用canvas.toBlob(function(blob){ //参数blob就已经是二进制文件了 });来把canvas转回二进制文件，但是我们使用提交表单的时候才即使转换的方式。
      		var smBase64 = canvas.toDataURL('image/jpeg', quality); //canvas转成新的base64数据，第二个参数为保存质量
      		//document.getElementById(input.name + '-v').src = smBase64; //赋值压缩后的base64图像
			
			
			$.ajax({
				url: "/ckfinder/controller.php?action=uploadimage",
				data: {
					img_data: smBase64
				},
				dataType: "json",
				type: "post",
				error: function () { },
				success: function (data) {
					   console.log(data);
					   if (data.state=='SUCCESS') {
						  $("#icon").val(data.url);
						  fileCid();
						  //layer.msg("图片上传成功")
						 // dialog({title:"提示",content:"图片上传成功",okValue:"确定",ok:function(){
						  //}}).showModal();
						  //$('#'+imgval+"_pic").attr('src',"https://csse.szu.edu.cn/courses/AdvTech2022"+data.url)
						 // $('#'+imgval).val(data.url)
					   }else{
						  layer.msg(data.state)
					   }
				}
			});
			
			
			
			
		};
		img.src = base64IMG; //这个可以放在onload后面的
	}
	reader.readAsDataURL(file);	//onload函数会在触发的时候才会执行
}

/*竖立形的手机图片压缩到高度为1000px，横幅型的图片压缩到宽度为1024px*/
function calcWH(ow, oh) {
	if (ow<1024 && oh<1000) {
		return {width: ow, height: oh};
	}
	if (ow>oh) { //横幅型 >1024px
		var height = Math.ceil(1024 / ow * oh); //向上取整
		return {width: 1024, height: height};
	}else{	//竖立型或正方形 >1000px
		var width = Math.ceil(1000 / oh * ow);
		return {width: width, height: 1000};
	}
}


input.addEventListener('change', startUpload)
