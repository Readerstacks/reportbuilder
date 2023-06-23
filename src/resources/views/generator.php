<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="<?php echo url('/public/ReportBuilder/script.js') ?>"></script>
<link rel="stylesheet" href="<?php echo url('/public/ReportBuilder/style.css') ?>" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/codemirror.min.js" integrity="sha512-05P5yOM5/yfeUDgnwTL0yEVQa0Cg6j3alVSbWSQtBxz24fERIyW3jeBdp7ZSHcgPMRYJWoa26IIWhJ2/UComLA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/mode/sql/sql.min.js" integrity="sha512-fb0A/RjJvLbWBSNDDNRUER4LHrkVQjlEs3a2myQH047y9+I6wZAZOboHn+EA7ZcEcVwSiH3okO/+XzMlGPqcow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/addon/hint/sql-hint.min.js" integrity="sha512-Pue0eeX9BJ4IA+BRNDOFwhQmxPjXIHiHOsvHNc9dQ+3J43swbPQDT9gwC8lzE1TTjR8iIxOd+lNiv4oTBRWqYw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/addon/hint/show-hint.min.css" integrity="sha512-W/cvA9Wiaq79wGy/VOkgMpOILyqxqIMU+rkneDUW2uqiUT53I6DKmrF4lmCbRG+/YrW0J69ecvanKCCyb+sIWA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/addon/hint/show-hint.min.js" integrity="sha512-4+hfJ/4qrBFEm8Wdz+mXpoTr/weIrB6XjJZAcc4pE2Yg5B06aKS/YLMN5iIAMXFTe0f1eneuLE5sRmnSHQqFNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/theme/base16-dark.min.css" integrity="sha512-CNZkbIVu/G4dB3YpFIZgMtE45vHp/QocgMbf6jg+DFFPLN3emncIob8ubKANmsGQ8JsnzzSVTj7WrFrnx6EgXQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<style>
    #main{
        display: flex;
        flex-direction: row
    }
    .cls70{
        width: 83%;
        float: left
    }
    .cls30{
        width: 15%
    }
    .head-title{
        margin:20px 0
    }
    #app_layout{
        display: flex;
         
    }
    .footer-sticky{
        float: right

    }
    .footer-sticky .btn{
        margin: 0.25rem 0.125rem;
        display: flex;
        flex-direction: column;
    }
    #metabase{
        display: flex;
        flex-direction: column;
        width: 70%;

    }
    #settings{
        width:    30%;
        height: 210px;
        overflow-x:auto;
        border:1px solid
    }
    
    .metabase_filters .colin{
        padding: 20px;
        /* padding-top: 20px; */
        
        width: 100%
    }
    
    .colin {
        float: left;
        margin-right: 10px
    }
</style>
 
<div id="app" class='container-fluid'>
<div class="  head-title">
Title : <input placeholder="Report name"    v-model="report_title" /> 
<?php
$database = config('database');
$default = $database['default'];
$connections = $database['connections'];


?>
Connection : <select v-model='connection'>
<?php 
foreach($connections as $name=>$connection)
{
?>
    <option   ><?php echo $name; ?></option>
<?php }
?>
</select>
</div>
 
    
         
            
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Layout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <select  v-model="layout" >
                                <option :value="name" v-for='(value, name ) of settings.layouts'> 
                                {{name}} 
                                </option>
                            </select>
                            <div v-if="settings.layouts &&  settings.layouts[layout] && settings.layouts[layout]['settings']">  
                                    Layout Settings
                                    <br>
                                    <div  v-for="(setting,sname) of settings.layouts[layout]['settings']">
                                    <span> {{sname}} : </span>
                                    <template v-if='typeof setting==="string"'>
                                       <input v-model="settings.layouts[layout]['settings'][sname]"  type='text' />
                                    </template>
                                    <template v-if='typeof setting!=="string"'>
                                       <select v-model="setting.value"   >
                                       <option v-for="item of setting.options" >{{item}}</option>
                                       </select>
                                    </template>
                                    </div>


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
                            <div class="colin" v-for="(k,inp) of vars"> 
                            
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
                
                <div class="metabase_editor">
            
                    <textarea    id='queryeditor' > 
                  
                </textarea> 
            
                
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
         
        
                 
            

    <div  style="width:100%" id='output'>
                
                <div id="filters" >
                </div>
            
                <div  id="outpuhtml" >
                </div>
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
                connection:'<?php echo $default; ?>',
                app: null,
                message: 'Hello Vue!',
                vars: {},
                json: {},
                share:{url:"",visibility:"Public",token:""},
                settings:{},
                filters_html:"",
                layout:'table',
                report_title:"Untitled",
                html:"",
                report_id:<?php echo request()->get("reportId",0) ?>,
                sql: ``,
                // editor:null
            }
        },
        mounted:function(){
              this.editor=CodeMirror.fromTextArea(document.getElementsByTagName("textarea")[0], {
        mode: "text/x-mysql",
        lineNumbers: true,
        theme:'base16-dark',    
        extraKeys: {"Ctrl-Space": "autocomplete"}, // To invoke the auto complete
        hint: CodeMirror.hint.sql,
        hintOptions: {
            tables: {
                "table1": [ "col_A", "col_B", "col_C" ],
                "table2": [ "other_columns1", "other_columns2" ]
            }
        }
}); 
this.editor.on('change', editor => {
 this.sql = this.editor.getValue();
   this.findVars()
});
setTimeout(()=>{
 this.findVars()
},1000)
 
            this.ajax(url+"/get-settings").then((data)=>{
                for(var lt in data.layouts){
                        data.layouts[lt]['title']=lt;
                 }
                 this.settings =data;
                 if(this.report_id>0){
                this.ajax(url+"/get-report-detail?reportId="+this.report_id).then((data)=>{
                    this.report_title       = data.title;
                    if(data.connection)
                    this.connection         = data.connection;
                    this.sql                = data.sql_query;
                    this.vars               = JSON.parse(data.filters);
                    this.share.visibility   = data.visibility || "Public";
                    this.share.url          = '<?php echo url("report-manager/report/"); ?>/'+data.uuid_token;
                    this.share.token        = data.token || "";
                    this.editor.setValue(this.sql);
                    this.findVars()
                    let layout= JSON.parse(data.layout);
                    for(let lt in this.settings.layouts){
                        console.log("this.settings.layouts",layout,lt)
                        if(layout["title"]==lt)
                        { 
                            this.layout = layout["title"];
                            this.settings.layouts[lt]=layout;
                        }
                    }
                    // this.layout =data.layout;
                    this.report_id = data.id;

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
                formData.append('sql', this.sql);
                formData.append('filters', JSON.stringify(this.vars));
                formData.append('connection',  (this.connection));
                formData.append('visibility', this.share.visibility);
                formData.append('token', this.share.token);
                formData.append('layout', JSON.stringify(this.settings.layouts[this.layout]));
                formData.append('report_id', this.report_id);
                
                

        
        ReportBuilder.ajax("/save-report",{method:"POST",body:formData}).then((data)=>{
                    // this.share.visibility = data.visibility || "Public";
                    // this.share.url      = "report-manager/report/"+data.id;
                    // this.share.token    = data.token || "";
                    this.report_id      =   data.data.id;
                    this.share.url      = '<?php echo url("report-manager/report/"); ?>/'+data.data.uuid_token;
                   
             console.log("data",data)
        })
    },

   

            
            run:function(){
                // ReportBuilder.init({
                //     url:url,
                //     el:'#outpuhtml',
                //     elFilter:"#filters",
                //     sql:this.sql,
                //     vars:this.vars,
                    
                // })
                ReportBuilder.data.url=url
                ReportBuilder.el("#outpuhtml")
                ReportBuilder.elFilter("#filters")
                ReportBuilder.
                setConnection(this.connection)
                .setReportCustom(this.sql,this.vars,JSON.stringify(this.settings.layouts[this.layout]))
                .getReportCustom();
                // var formData = new FormData();
                // formData.append('filters', JSON.stringify(this.vars));
                // formData.append('sql', this.sql);
                // this.filters_html ='';
                // this.ajax(url+"/get-report",{method:"POST",body:formData}).then((data)=>{
                //        this.html=data['layout'].html; 
                //        for(let name in data['inputs']){
                //          this.filters_html += data['inputs'][name].html;
                //          setTimeout(()=>{
                //             this.loadScripts(data['inputs'][name].scripts,0,data['inputs'][name]['input_type'].split(" ").join("_"))
                //             this.loadStyle(data['inputs'][name].styles,0,data['inputs'][name]['input_type'].split(" ").join("_"))  

                //          },100)
                //        }

                //     //  document.getElementById("input_"+input.name).innerHTML=data.html;
                //     //  this.loadScripts(data.scripts,0,e.target.value.split(" ").join("_"))
                
                //     //  this.loadStyle(data.styles,0,e.target.value.split(" ").join("_"))

                //  })
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
<script>


 
 
// CodeMirror.commands.autocomplete = function(cm) {
//     CodeMirror.showHint(cm, CodeMirror.hint.sql, { 
//         tables: {
//             "table1": [ "col_A", "col_B", "col_C" ],
//             "table2": [ "other_columns1", "other_columns2" ]
//         }
//     } );
// }
</script>