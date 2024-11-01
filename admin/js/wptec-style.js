//  Tables JS style by wptec 
//  if page is not false 
if(wptecStyleData.page ){
    //  For loop widths 
    for(const key in wptecStyleData.width){
        // if value is not empty 
        if(wptecStyleData.width[key] != 0){
            // check if this element present in the DOM tree 
            if(document.getElementById(key)){
                //  Setting Table column width  measurement units by percent or Pixel 
                if(wptecStyleData.measurement == "Percent"){
                    document.getElementById(key).style.width = wptecStyleData.width[key] + "%";
                }else{
                    document.getElementById(key).style.width = wptecStyleData.width[key] + "px";
                }
            }
        }
    }
}

