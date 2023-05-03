var ReportBuilder = {

    data: {url:"",vars:{},sql:"",filters_html:"",html:"",el:"#app"},
    loadStyle:  function  (scripts,index,name ){
        var scln=Object.keys(scripts).length;
        console.log(scln,index)
        if(index<scln){
                    var ind=Object.keys(scripts)[index];
                    console.log(ind,'ind')
                    var script= scripts[ind];
                        var inpname= name+"_"+ind+"_style";
                        console.log(inpname,script,'inpname')
                     if( !document.getElementById(inpname)){
                         
                          
                          var my_awesome_script = document.createElement('link');
                          my_awesome_script.setAttribute("id",inpname)
                          my_awesome_script.rel = "stylesheet";
                          my_awesome_script.type = "text/css";
                          my_awesome_script.href = script['src']
                           document.head.appendChild(my_awesome_script);
                            my_awesome_script.onload= ()=>{
                                this.loadStyle(scripts,index+1,name);
                            }
                           
                          
                          
                      }
                      else{
                          
                            this.loadStyle(scripts,index+1,name);
                      }
        }
    },
    loadScripts:function   (scripts,index,name)  {
        var scln=Object.keys(scripts).length;
        console.log(scln,index)
        if(index<scln){
                    var ind=Object.keys(scripts)[index];
                    console.log(ind,'ind')
                    var script= scripts[ind];
                        var inpname= name+"_"+ind+"_script";
                        console.log(inpname,script,'inpname')
                     if(script['src'] && !document.getElementById(inpname)){
                         
                          
                          var my_awesome_script = document.createElement('script');
                          my_awesome_script.setAttribute("id",inpname)
                          my_awesome_script.setAttribute('src',script['src']);
                           document.head.appendChild(my_awesome_script);
                            my_awesome_script.onload= ()=>{
                                this.loadScripts(scripts,index+1,name);
                            }
                           
                          
                          
                      }
                      else{
                          if(!script['src']){
                            eval( script['text']);
                          }
                          else
                            this.loadScripts(scripts,index+1,name);
                      }
        }
    },

    ajax:function(...arguments){
        arguments[0]=this.data.url+arguments[0];

        return   fetch(...arguments).then((data)=>{
            return data.json();
        });
    },
    mounted:function(){
        this.ajax("/get-settings").then((data)=>{
            
             this.settings =data;
        })
    },
    getReport:function(id){
        var formData = new FormData();
        
        formData.append('id', id);
        this.filters_html ='';
        this.ajax("/get-report",{method:"POST",body:formData}).then((data)=>{
        
            this.handleReportData(data)
        })
    },

    el:function(selector){
        this.data.el=selector;
    },

   
    getReportCustom:function(sql,vars){
        var formData = new FormData();
        
        
        formData.append('sql', sql);
        formData.append('filters', JSON.stringify(vars));
        this.filters_html ='';
        this.ajax("/get-report",{method:"POST",body:formData}).then((data)=>{
            this.handleReportData(data)
        })
    },
    handleReportData:function(data){
        this.data.html=data['layout'].html; 
        for(let name in data['inputs']){
            this.data.filters_html += data['inputs'][name].html;
            setTimeout(()=>{
                this.loadScripts(data['inputs'][name].scripts,0,data['inputs'][name]['input_type'].split(" ").join("_"))
                this.loadStyle(data['inputs'][name].styles,0,data['inputs'][name]['input_type'].split(" ").join("_"))  
            },100)
        }
       ;
        document.querySelector( this.data.el).innerHTML =this.data.html;
    },
}
// ReportBuilder.getReportCustom