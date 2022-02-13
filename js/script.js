function on() {
        var divsToHide = document.getElementsByClassName("card"); 
       if(divsToHide.length>0){
               for(var i = 0; i < divsToHide.length; i++){
       
             if( divsToHide[i].style.display== "none"){
           divsToHide[i].style.display = "block";
             }else{
       
                  divsToHide[i].style.display = "none"; 
             }    
       }}
}


function shareOnFB(siteURL, title){
  var url = 'https://www.facebook.com/sharer/sharer.php?u=' + siteURL + '&text=#popcorntime Just watched ' + title + ' on ' + siteURL;
  window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
  return false;
}


function shareOnTwitter(siteURL, title){
var url = 'https://twitter.com/intent/tweet?url='+siteURL+'&text=#popcorntime Just watched ' + title + ' on ' + siteURL;
TwitterWindow = window.open(url, 'TwitterWindow',width=600,height=300);
return false;
}