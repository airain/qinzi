function GB2312UTF8(){
	this.Dig2Dec=function(s){
		  var retV = 0;
		  if(s.length == 4){
			  for(var i = 0; i < 4; i ++){
				  retV += eval(s.charAt(i)) * Math.pow(2, 3 - i);
			  }
			  return retV;
		  }
		  return -1;
	}
	this.Hex2Utf8=function(s){
		 var retS = "";
		 var tempS = "";
		 var ss = "";
		 if(s.length == 16){
			 tempS = "1110" + s.substring(0, 4);
			 tempS += "10" + s.substring(4, 10);
			 tempS += "10" + s.substring(10,16);
			 var sss = "0123456789ABCDEF";
			 for(var i = 0; i < 3; i ++){
				retS += "%";
				ss = tempS.substring(i * 8, (eval(i)+1)*8);
				retS += sss.charAt(this.Dig2Dec(ss.substring(0,4)));
				retS += sss.charAt(this.Dig2Dec(ss.substring(4,8)));
			 }
			 return retS;
		 }
		 return "";
	}
	this.Dec2Dig=function(n1){
		  var s = "";
		  var n2 = 0;
		  for(var i = 0; i < 4; i++){
			 n2 = Math.pow(2,3 - i);
			 if(n1 >= n2){
				s += '1';
				n1 = n1 - n2;
			  }
			 else
			  s += '0';
		  }
		  return s;    
	}

	this.Str2Hex=function(s){
		  var c = "";
		  var n;
		  var ss = "0123456789ABCDEF";
		  var digS = "";
		  for(var i = 0; i < s.length; i ++){
			 c = s.charAt(i);
			 n = ss.indexOf(c);
			 digS += this.Dec2Dig(eval(n));
		  }
		  return digS;
	}
	this.Gb2312ToUtf8=function(s1){
		var s = escape(s1);
		var sa = s.split("%");
		var retV ="";
		if(sa[0] != ""){
		  retV = sa[0];
		}
		for(var i = 1; i < sa.length; i ++){
		  if(sa[i].substring(0,1) == "u"){
			retV += this.Hex2Utf8(this.Str2Hex(sa[i].substring(1,5)));
	   if(sa[i].length){
		retV += sa[i].substring(5);
	   }
		  }
		  else{
		 retV += unescape("%" + sa[i]);
	   if(sa[i].length){
		retV += sa[i].substring(5);
	   }
	   }
		}
		return retV;
	}
	this.Utf8ToGb2312=function(str1){
			var substr = "";
			var a = "";
			var b = "";
			var c = "";
			var i = -1;
			i = str1.indexOf("%");
			if(i==-1){
			  return str1;
			}
			while(i!= -1){
		if(i<3){
					substr = substr + str1.substr(0,i-1);
					str1 = str1.substr(i+1,str1.length-i);
					a = str1.substr(0,2);
					str1 = str1.substr(2,str1.length - 2);
					if(parseInt("0x" + a) & 0x80 == 0){
					  substr = substr + String.fromCharCode(parseInt("0x" + a));
					}
					else if(parseInt("0x" + a) & 0xE0 == 0xC0){ //two byte
							b = str1.substr(1,2);
							str1 = str1.substr(3,str1.length - 3);
							var widechar = (parseInt("0x" + a) & 0x1F) << 6;
							widechar = widechar | (parseInt("0x" + b) & 0x3F);
							substr = substr + String.fromCharCode(widechar);
					}
					else{
							b = str1.substr(1,2);
							str1 = str1.substr(3,str1.length - 3);
							c = str1.substr(1,2);
							str1 = str1.substr(3,str1.length - 3);
							var widechar = (parseInt("0x" + a) & 0x0F) << 12;
							widechar = widechar | ((parseInt("0x" + b) & 0x3F) << 6);
							widechar = widechar | (parseInt("0x" + c) & 0x3F);
							substr = substr + String.fromCharCode(widechar);
					}
		 }
		 else {
		  substr = substr + str1.substring(0,i);
		  str1= str1.substring(i);
		 }
				  i = str1.indexOf("%");
			}

			return substr+str1;
	}
}

function goUrl(v,url)
{
	location.href=url+v;
}

function computeDealTime(today,endtime){
	var t_time = today.replace(/(:)|(-)|( )/g,',');
	var e_time = endtime.replace(/(:)|(-)|( )/g,',');
	eval("var now_date = new Date("+t_time+");");
	eval("var end_date = new Date("+e_time+");");
	
	
	var diff_time =  end_date.getTime() - now_date.getTime();
	
	var diff_dates = 0;   //天
	var diff_hours = 0;   //时
	var diff_mins  = 0;   //分
	var diff_secs  = 0;   //秒

	if(diff_time > 0){
		diff_dates = Math.floor(diff_time / (1000 * 3600 * 24));
		diff_hours = Math.floor((diff_time - ((diff_dates)*24*3600*1000)) / (1000 * 3600));
		diff_mins = Math.floor((diff_time - ((diff_dates)*24*3600*1000) - ((diff_hours) *3600*1000)) / (1000 * 60));
		diff_secs = Math.floor((diff_time - ((diff_dates)*24*3600*1000) - ((diff_hours) *3600*1000) - ((diff_mins) *60*1000)) / (1000));
	}
	return diff_dates+'天'+diff_hours+'小时'+diff_mins+'分'+diff_secs+'秒';
}

//获取cookie
function getCookie(name){
	var result = null; 
	var myCookie = document.cookie + ";"; 
	var searchName = name + "="; 
	var startOfCookie = myCookie.indexOf(searchName); 
	var endOfCookie; 
	if (startOfCookie != -1)
	{ 
		startOfCookie += searchName.length; 
		endOfCookie = myCookie.indexOf(";", startOfCookie); 
		result = unescape(myCookie.substring(startOfCookie, endOfCookie)); 
	} 
	return result; 
}

//设置cookie
function setCookie(name, value, expires, path, domain, secure){
	var expDays = expires * 24 * 60 * 60 * 1000;
	var expDate = new Date(); 
	expDate.setTime(expDate.getTime() + expDays); 
	var expString = ((expires == null) ? "" : (";expires=" + expDate.toGMTString())) 
	var pathString = ((path == null) ? "" : (";path=" + path)) 
	var domainString = ((domain == null) ? "" : (";domain=" + domain)) 
	var secureString = ((secure == true) ? ";secure" : "" )
	document.cookie = name + "=" + escape(value) + expString + pathString + domainString + secureString;
}



	   function getProvinces(selProvance)
        {
            selProvance.options.length = 0;
            
            var pAs = provincesData.split(",");
            for(var pA in pAs)
            {
                var pA_parts = pAs[pA].split("|");
                selProvance.options.add(new Option(pA_parts[1],pA_parts[0]));
            }
            
            if(selProvance.options.length == 0) 
            { 
                selProvance.disabled = true; 
                selProvance.options.add(new Option("","000000"));
            }
            else
            {
                selProvance.disabled = false;
            }
            
            selProvance.options[0].selected = true;
        }
        
       function getCitys(selCity,pv)
        {
            selCity.options.length = 0;
            
            var cAs = citysData.split(",");
            for(var cA in cAs)
            {
                var cA_parts = cAs[cA].split("|");
                
                if(pv.substring(0,2) == cA_parts[0].substring(0,2))
                {
                    selCity.options.add(new Option(cA_parts[1],cA_parts[0]));
                }
            }
            
            if(selCity.options.length == 0) 
            { 
                selCity.disabled = true; 
                selCity.options.add(new Option("","000000"));
            }
            else
            {
                 selCity.disabled = false;
            }
            
            selCity.options[0].selected = true;
            
        }
        
     function getAreas(selArea,cv)
        {
            selArea.options.length = 0;
            
            var aAs = areasData.split(",");
            for(var aA in aAs)
            {
                var aA_parts = aAs[aA].split("|");
                
                if(cv.substring(0,4) == aA_parts[0].substring(0,4))
                {
                    selArea.options.add(new Option(aA_parts[1],aA_parts[0]));
                }
            }
            
            if(selArea.options.length == 0) 
            { 
                selArea.disabled = true; 
                selArea.options.add(new Option("","000000"));
            }
            else
            {
                 selArea.disabled = false;
            }
            
            selArea.options[0].selected = true;
        }
        
        function loadData(selProvance,selCity,selArea)
        {
            getProvinces(selProvance);
            getCitys(selCity,selProvance.options[selProvance.selectedIndex].value);
            getAreas(selArea,selCity.options[selCity.selectedIndex].value);
        }
        function chgProvinces(selProvance,selCity,selArea)
        {
            getCitys(selCity,selProvance.options[selProvance.selectedIndex].value);
            getAreas(selArea,selCity.options[selCity.selectedIndex].value);
        }
        function chgCitys(selCity,selArea)
        {
            getAreas(selArea,selCity.options[selCity.selectedIndex].value);
        }

		function iniArea(provances,citys,areas)
		{
            getProvinces(selProvance);
			document.getElementById('selProvance').value = provances ; 

			var pr = document.getElementById('selProvance');
			var ct = document.getElementById('selCity');

            getCitys(selCity,pr.options[pr.selectedIndex].value);
			document.getElementById('selCity').value = citys; 
			
            getAreas(selArea,ct.options[ct.selectedIndex].value);
			document.getElementById('selArea').value = areas;
		}

		function getProvinceById(pid)
		{
			var pAs = provincesData.split(",");
			var p_name = '';
            for(var pA in pAs)
            {
                var pA_parts = pAs[pA].split("|");
				if(pid == pA_parts[0]){
					p_name = pA_parts[1];
					break;
				}
            }
			return p_name;
		}

		function getCityById(cid)
		{           
            var cAs = citysData.split(",");
			var c_name = '';
            for(var cA in cAs)
            {
                var cA_parts = cAs[cA].split("|");
				if(cid == cA_parts[0]){
					c_name = cA_parts[1];
					break;
				}
            }
			return c_name;
		}

		function getAreaById(aid)
		{           
            var cAs = areasData.split(",");
			var c_name = '';
            for(var cA in cAs)
            {
                var cA_parts = cAs[cA].split("|");
				if(aid == cA_parts[0]){
					c_name = cA_parts[1];
					break;
				}
            }
			return c_name;
		}

function delShopUploadImg(shop_id,img_name)
{
	$.get('/index.php/admin-shop/delete_upload_img',{shop_id:shop_id,img_name:img_name},function(data){
		data = eval('('+data+')');
		if(data.result){
			
		}
	
	})

}

