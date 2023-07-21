<script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/8.1.0/gridstack-all.min.js" integrity="sha512-Yu/575sLsJNIParTS2bKHVRwUHPmFiwtQ7ZK6RZ9GtGS8Pn3lqzij0d3akUEO5NdiXFrNBa1Bwpw6T8oaHI1Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/8.1.0/gridstack.min.css" integrity="sha512-Sp5X7gkrXd6GP4ILw5J981IMw/pfsg65BjhYBsy2oNxUKvRULyoJVw377OEGf3+soo7LI6mfIbTks6SS6FV5SA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="<?php echo url('/public/ReportBuilder/script.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

<style type="text/css">
  .grid-stack { background: white; }
  .cls70{width:100%}
  .grid-stack-item-content { background-color: #18BC9C; }
</style>

 
 
<div id="app" class='container-fluid'>
<div class="  head-title">
Title : <input placeholder="Dashboard name"    v-model="dashboard_title" /> 
 
 
</div>
 
    
         
            
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                     
                            <div   v-for="report of reports">
                                 {{report.title}} 
                                 <button @click="addToDashboard(createNode(report))">Add</button>
                            </div>
                            
                        </div>
                </div>

                <div class="offcanvas offcanvas-start" tabindex="-1" id="variables-settings" aria-labelledby="offcanvasExampleLabel1">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel1">Variables</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="metabase_filters" >
                            <button type="button" class="btn btn-primary"  @click='addVariables()'>Add New</button>
                            <div class="colin" v-for="(k,inp) of vars"> 
                                <button @click='deleteVar(inp)'>Delete</button>
                                <span>{{k.title}} : </span>
                                
                                <br> <input :placeholder="k.title"    v-model="k.title" />
                                <br>
                                Type:   
                                <br> <select @change="buildInput(k,$event)" v-model="k.type" >

                                    <option :value="name" v-for='(value, name ) of settings.filters'> 
                                        {{name}} 
                                    </option>
                                </select>
                                <div v-if="k.type && k.settings">  
                                        Input Settings
                                        <br>
                                        <div  v-for='(setting,sname) of k.settings'>
                                        <span> {{sname}} : </span>
                                      
                                        <template v-if='typeof setting==="string"'>
                                        <input v-model='k.settings[sname]'  type='text' />
                                        </template>
                                        <template v-if='typeof setting!=="string"'>
                                            <select v-model="setting.value"   >
                                            <option v-for="item of setting.options" >{{item}}</option>
                                            </select>
                                        </template>
                                        </div>


                                </div>
                                <br>
                                Hidden:   
                                <br>  <select v-model="k.hidden" ><option value='1'> Yes</option> <option value='0'>No</option></select>
                                <br>
                                <br>
                                Default Value:   
                                <br>  <input placeholder="value"    v-model="k.value" />
                                <br>
                                Required:   
                                <br>  <select v-model="k.required" ><option value='1'> Yes</option> <option value='0'>No</option></select>
                                <br>

                                Mapping: 

                                <br>
                                <div v-for='widget of items'  >
                                    {{widget.title}}  
                                    <select v-model='widget.mappers[inp]' > <option v-for='(filter,filter_name) of widget.filters'>{{filter_name}}</option>
                                    </select>
                                </div>
                                <br>

                                
                            </div>
                        </div> 
                    </div>
                </div>
           
                  
                <div class="offcanvas offcanvas-start" tabindex="-1" id="share-settings" aria-labelledby="offcanvasExampleLabel2">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Share Settings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">URL</label>
                            <input type="url" v-model='share.url' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Visibility</label>
                            <select  v-model='share.visibility' class="form-control" id="exampleFormControlSelect1">
                            <option>Public</option>
                            <option>Private</option>
                            <option>Protected</option>
                            
                            </select>
                        </div>
                        <div v-if='share.visibility=="Protected"' class="form-group">
                            <label for="exampleInputEmail1">Password or Token</label>
                            <input  v-model='share.token' type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter">
                        </div>   
                        <button @click='saveReport()' data-bs-dismiss="offcanvas" aria-label="Close" type="button" class="btn btn-primary">Share</button>
                    </div>
                </div>      
            

        
        <div id="" style='display:flex'>
            <div class='cls70'>
                <div class="metabase_filters " style="padding-bottom:10px" >
                    <div class='row'>
                    <div :id='"input_"+k.name' class='col'  v-for="(k,inp) of vars"> <span>{{k.title}} : </span><br> <input :placeholder="k.title" :name="k.name" :type="k.type" v-model="k.value" /> </div>
                    </div>
                </div>   
                
                    <div class='grid-stack' style='height:500px'> 
                        <div v-for="(w, indexs) in items" class="grid-stack-item" :gs-x="w.x" :gs-y="w.y" :gs-w="w.w" :gs-h="w.h"
                            :gs-id="w.sid" :id="w.sid" :key="w.sid">
                            <div class="grid-stack-item-content">
                                <button @click="remove(w)">X</button>
                                <iframe :src='url+"/report/"+w.uid+"?hide_filters=true"'   frameborder="0"
 style="position: relative; height: 90%; width: 100%;" >
                                </iframe>
                            </div>
                        </div>
                    </div>  
            </div>
           <Div class='settingsbtn cls30'>
           <div class='footer-sticky'>
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
              Select Layout
            </button>
            <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#variables-settings" aria-controls="offcanvasExample">
             Variables
            </button>
            <button v-if='share.url!=""' class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#share-settings" aria-controls="offcanvasExample">
             Share
            </button>
            
            <button type="button"  class="btn btn-success" @click='run()'>Run</button>
            <button type="button"  class="btn btn-danger" @click='saveReport()'>Save</button>
        </div>
           </Div>

        </div>
         
        
                 
            

  
    
</div>

<script>
    let url ="<?php echo url("report-manager"); ?>"
 
    const {
        createApp,
        h
    } = Vue
 
    createApp({
        components: {},
        data() {
            return {
                
                app: null,
                reports:[],
                message: 'Hello Vue!',
                vars: {},
                items:[],
                json: {},
                share:{url:"",visibility:"Public",token:""},
                settings:{},
                filters_html:"",
                
                dashboard_title:"Untitled",
                html:"",
                url:url,
                dashboard_id:'<?php echo request()->get("dashboardId",'') ?>',
             
                // editor:null
            }
        },
        mounted:function(){
            
            var items = [
    // {content: 'my first widget'}, // will default to location (0,0) and 1x1
    // {w: 2, content: 'another longer widget!'} // will be placed next at (1,0) and 2x1
  ];
  
    this.grid = GridStack.init();
    this.grid.load(this.items);
    this.grid.on("dragstop", function (event, element) {
              const node = element.gridstackNode;
            //   info.value = `you just dragged node #${node.id} to ${node.x},${node.y} – good job!`;
    });
            
    this.grid.on('change', onChange.bind(this));
    function onChange(event, changeItems) {
            // this.updateInfo();
            // update item position
            console.log("changeItems",changeItems, this.items)
            changeItems.forEach(item => {
              var widget = this.items.find(w => w.sid == item.id);
              if (!widget) {
                alert("Widget not found: " + item.id);
                return;
              }
              widget.x = item.x;
              widget.y = item.y;
              widget.w = item.w;
              widget.h = item.h;            
            });
          }
  this.ajax(url+"/get-settings").then((data)=>{
           
                 this.settings =data;
                });
            this.ajax(url+"/get-all-reports").then((data)=>{
           
                       this.reports =data.data
                console.log("this.reports",data)
                //  this.settings =data;
                 if(this.dashboard_id!=''){
                this.ajax(url+"/get-dashboard?dashboardId="+this.dashboard_id).then((data)=>{
                    this.dashboard_title       = data.title;
                   
                    
                    this.share.visibility   = data.visibility || "Public";
                    this.share.url          = '<?php echo url("report-manager/dashboard/"); ?>/'+data.uuid_token;
                    this.share.token        = data.token || "";
                      
                    this.dashboard_id = data.id;
                    let layout= JSON.parse(data.layout);
                    let filters= JSON.parse(data.filters);

                    for(let rpt of layout){
                        this.addToDashboard(rpt)
                    }

                    for(let rpt in filters){
                        this.addFilter(rpt,filters[rpt])
                    }
                    

                })
                }      

            })
           
        },
        methods: {
            ajax:function(...arguments){
                 
                return   fetch(...arguments).then((data)=>{
                    return data.json();
                });
            },
            createNode:function(report){
                const node = {sid:"w_"+report.id,id:report.id,title:report.title,
                    mappers:{},
                    uid:report.uuid_token,filters:JSON.parse(report.filters), 
                    x: 0, y: 0, w: 2, h: 2 };
                    return node;
            },
            addToDashboard:function (node){  
                 
                    
        //          grid.addWidget(node);
        
                    this.items.push(node);
                    setTimeout(()=>{
                        this.grid.makeWidget(node.sid);
                    },1000)
                    // Vue.nextTick(()=>{
                      
                    //     // updateInfo();
                    // });
    //             var html=`<div style="position: relative; height: 100%; width: 100%;" > 
    //             <div style='height:10%'>Drag</div>
    //             <iframe src='${url+"/report/"+report.uuid_token}?hide_filters=true'   frameborder="0"
    // style="position: relative; height: 90%; width: 100%;" /><div>`  ;
    //             this.grid.addWidget({x:0, y:0, w:4, content: html});
            },
             remove:function(widget) {
            var index = this.items.findIndex(w => w.id == widget.id);
            this.items.splice(index, 1);
            const selector = `#${widget.sid}`;
            this.grid.removeWidget(selector, false);
            // updateInfo();            
          },
            addVariables:function(){
                var capturedGroup=prompt("Enter name ?");
                this.addFilter(capturedGroup,{});
            },

            deleteVar:function(name){
                var sure=confirm("Are you sure?");
                if(sure){
                  delete  this.vars[name] 
                }
                
            },
            addFilter:function(capturedGroup,defaults){
                if(capturedGroup){
                let filter= Object.keys(this.settings.filters)[0];
                        let input = JSON.parse(JSON.stringify(this.settings.filters[filter]));
                        
                        this.vars[capturedGroup] = {
                            type:  defaults.type || filter,
                            class: defaults.class ||  input.class,
                            settings: defaults.settings || input.settings,
                            name: capturedGroup,
                            title: capturedGroup.charAt(0).toUpperCase() + capturedGroup.slice(1),
                            required: '0',
                            value: "",
                            hidden:'0'
                        };
                     
                    }
            }
            ,
            buildInputs:function(input,e){

                var formData = new FormData();
                formData.append('input', e.target.value);
                formData.append('config', JSON.stringify(input));

                this.ajax(url+"/get-input",{method:"POST",body:formData}).then((data)=>{
                    
                    document.getElementById("input_"+input.name).innerHTML=data.html;
                    this.loadScripts(data.scripts,0,e.target.value.split(" ").join("_"))

                    this.loadStyle(data.styles,0,e.target.value.split(" ").join("_"))

                })
            },
            buildInput:function(input,e){
               
                if(this.settings.filters[input.type] && this.settings.filters[input.type]['settings']){
                    input.class= this.settings.filters[input.type]['class'];
                    input.settings=JSON.parse(JSON.stringify(this.settings.filters[input.type]['settings']));  
                }
                
                // var formData = new FormData();
                // formData.append('input', e.target.value);
                // formData.append('config', JSON.stringify(input));

                // this.ajax(url+"/get-input",{method:"POST",body:formData}).then((data)=>{
                    
                //     //  document.getElementById("input_"+input.name).innerHTML=data.html;
                //     //  this.loadScripts(data.scripts,0,e.target.value.split(" ").join("_"))
                
                //     //  this.loadStyle(data.styles,0,e.target.value.split(" ").join("_"))

                //  })
            },
            loadStyle:function(scripts,index,name){
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
            loadScripts:function(scripts,index,name){
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

            saveReport:function(){
                ReportBuilder.data.url=url
                var formData = new FormData();
        
                formData.append('title', this.report_title);
                // formData.append('items', JSON.stringify(this.items));
                formData.append('filters', JSON.stringify(this.vars));
                formData.append('connection',  (this.connection));
                formData.append('visibility', this.share.visibility);
                formData.append('token', this.share.token);
                formData.append('layout', JSON.stringify(this.items));
                formData.append('dashboard_id', this.dashboard_id);
                
                

        
                ReportBuilder.ajax("/save-dashboard",{method:"POST",body:formData}).then((data)=>{
                            
                            this.dashboard_id      =   data.data.id;
                            this.share.url      = '<?php echo url("report-manager/dashboard/"); ?>/'+data.data.uuid_token;
                        
                    console.log("data",data)
                })
    },

   

            
            run:function(){
              
                ReportBuilder.data.url=url
                ReportBuilder.el("#outpuhtml")
                ReportBuilder.elFilter("#filters")
                ReportBuilder.
                setConnection(this.connection)
                .setReportCustom(this.sql,this.vars,JSON.stringify(this.settings.layouts[this.layout]))
                .getReportCustom();
                
            },

            filterResults:function(e){
                e.preventDefault()
                console.log(this.$refs.form,e)
                var formData = new FormData(e.form);
                
                console.log("formData",formData,)
                return false;
            },


            getStringBetween: function(str, start, end) {
                const vars = [];
                const text = str;
                const pattern = /{{\s*([a-zA-Z0-9_]+)\s*}}/g;
                let match;
                var origin = this.vars;
                var newo = {};
                while ((match = pattern.exec(text)) !== null) {
                    const capturedGroup = match[1];
                    if (origin[capturedGroup]) {
                        newo[capturedGroup] = origin[capturedGroup];
                    } else if (!origin[capturedGroup]) {
                        let filter= Object.keys(this.settings.filters)[0];
                        let input = JSON.parse(JSON.stringify(this.settings.filters[filter]));
                        
                        newo[capturedGroup] = {
                            type: filter,
                            class: input.class,
                            settings: input.settings,
                            name: capturedGroup,
                            title: capturedGroup.charAt(0).toUpperCase() + capturedGroup.slice(1),
                            required: '0',
                            value: "",
                            hidden:'0'
                        };
                    }
                }
                this.vars = newo
                return vars;
            },

            findVars: function() {
                this.getStringBetween(this.sql, '{{', '}}');
            },
            
        }
    }).mount('#app')
</script> 