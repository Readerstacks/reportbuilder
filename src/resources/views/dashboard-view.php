<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/8.1.0/gridstack-all.min.js" integrity="sha512-Yu/575sLsJNIParTS2bKHVRwUHPmFiwtQ7ZK6RZ9GtGS8Pn3lqzij0d3akUEO5NdiXFrNBa1Bwpw6T8oaHI1Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/gridstack.js/8.1.0/gridstack.min.css" integrity="sha512-Sp5X7gkrXd6GP4ILw5J981IMw/pfsg65BjhYBsy2oNxUKvRULyoJVw377OEGf3+soo7LI6mfIbTks6SS6FV5SA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="<?php echo url('/public/ReportBuilder/script.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="<?php echo url('/public/ReportBuilder/script.js') ?>"></script>

<style type="text/css">
  .grid-stack { background: white; }
  .cls70{width:100%;};
  .grid-stack-item-content {  }
</style>



<div id="app" class='container-fluid'>














        <div id="" style='display:flex'>
            <div class='cls70'>
                <div class="metabase_filters " id="filters" style="padding-bottom:10px" >

                </div>

                    <div class='grid-stack'  >
                        <div v-for="(w, indexs) in items" class="grid-stack-item" :gs-x="w.x" :gs-y="w.y" :gs-w="w.w" :gs-h="w.h"
                            :gs-id="w.sid" :id="w.sid" :key="w.sid">
                            <div class="grid-stack-item-content">

                                <iframe :src='url+"/report/"+w.uid+"?hide_filters=true&"+w.filters'   frameborder="0"
 style="position: relative; height: 90%; width: 100%;" >
                                </iframe>
                            </div>
                        </div>
                    </div>
            </div>


        </div>







</div>
<script>
 let url ="<?php echo url("report-manager"); ?>"
        //
                ReportBuilder.data.url=url
                ReportBuilder.el("#outpuhtml")
                ReportBuilder.elFilter("#filters")


    </script>
<script>


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
                dashboard_id:'<?php echo $dashboardid; ?>',

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

  this.ajax(url+"/get-settings").then((data)=>{

                 this.settings =data;
                });




                console.log("this.reports",this.dashboard_id)
                //  this.settings =data;
                 if(this.dashboard_id!=''){
                 ReportBuilder.setDashboardId('<?php echo $dashboardid ;?>','public').getDashboardReport((data,filters)=>{
                    this.dashboard_title       = data.title;
                    this.items=[];
                   // this.grid.load(JSON.parse(data.layout));
                    console.log("data,filters",data,filters)
                   let items= JSON.parse(data.layout);
                   this.grid.removeAll(false);
                   for(let item of items){
                    let mappedFilters={};
                    for(let mapper in item.mappers){
                        if(filters[mapper]){
                            mappedFilters[item.mappers[mapper]]=filters[mapper]
                        }
                    }
                    // item.noResize=true;
                    // item.noMove=true;
                    item.filters=this.serialize(mappedFilters);
                       this.items.push(item);

                   }
                   setTimeout(()=>{
                       for(let widget of this.items){
                       this.grid.makeWidget(widget.sid);
                       this.grid.enableMove(false);
                       this.grid.enableResize(false);
                       }
                   })

                   this.share.visibility   = data.visibility || "Public";
                   this.share.url          = '<?php echo url("report-manager/dashboard/"); ?>/'+data.uuid_token;
                   this.share.token        = data.token || "";

                   this.dashboard_id = data.id;
                 }).then((data)=>{




                })
                }



        },
        methods: {
            serialize : function(obj) {
  var str = [];
  for (var p in obj)
    if (obj.hasOwnProperty(p)) {
      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
    }
  return str.join("&");
},
            ajax:function(...arguments){

                return   fetch(...arguments).then((data)=>{
                    return data.json();
                });
            },




            addToDashboard:function (report){
                //   const node = {sid:"w_"+report.id,id:report.id,title:report.title,
                //     mappers:{},
                //     uid:report.uuid_token,filters:JSON.parse(report.filters),
                //     x: 0, y: 0, w: 2, h: 2 };

        //          grid.addWidget(node);



                    // Vue.nextTick(()=>{

                    //     // updateInfo();
                    // });
    //             var html=`<div style="position: relative; height: 100%; width: 100%;" >
    //             <div style='height:10%'>Drag</div>
    //             <iframe src='${url+"/report/"+report.uuid_token}?hide_filters=true'   frameborder="0"
    // style="position: relative; height: 90%; width: 100%;" /><div>`  ;
    //             this.grid.addWidget({x:0, y:0, w:4, content: html});
            },



        }
    }).mount('#app')
</script>
