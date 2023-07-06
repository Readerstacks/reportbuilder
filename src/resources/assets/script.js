var ReportBuilder = {

    data: {
        url: "",
        vars: {},
        sql: "",
        filters_html: "",
        html: "",
        el: "#app",
        elFilter: "#filters",
        reportId: "",
        visibility: "Public",
        layout: "table",
        parameters: {},
        password: "",
        title: "New Question",
        dashboarChangeListner: null,
        connection: "",
        response: {},
        reportType: "REPORT",
        layouts: [],
    },
    setConnection: function (connection) {
        this.data.connection = connection;
        return this;
    },
    loadStyle: function (scripts, index, name) {
        var scln = Object.keys(scripts).length;
        console.log(scln, index)
        if (index < scln) {
            var ind = Object.keys(scripts)[index];
            console.log(ind, 'ind')
            var script = scripts[ind];
            var inpname = name + "_" + ind + "_style";
            console.log(inpname, script, 'inpname')
            if (!document.getElementById(inpname)) {


                var my_awesome_script = document.createElement('link');
                my_awesome_script.setAttribute("id", inpname)
                my_awesome_script.rel = "stylesheet";
                my_awesome_script.type = "text/css";
                my_awesome_script.href = script['src']
                document.head.appendChild(my_awesome_script);
                my_awesome_script.onload = () => {
                    this.loadStyle(scripts, index + 1, name);
                }



            }
            else {

                this.loadStyle(scripts, index + 1, name);
            }
        }
    },
    loadScripts: function (scripts, index, name) {
        var scln = Object.keys(scripts).length;
        console.log(scln, index)
        if (index < scln) {
            var ind = Object.keys(scripts)[index];
            console.log(ind, 'ind')
            var script = scripts[ind];
            var inpname = name + "_" + ind + "_script";
            console.log(inpname, script, 'inpname')
            if (!script['text'] && !document.getElementById(inpname)) {


                var my_awesome_script = document.createElement('script');
                my_awesome_script.setAttribute("id", inpname)
                if (script['text']) {
                    my_awesome_script.text = script['text'];
                }
                else {
                    my_awesome_script.setAttribute('src', script['src']);
                }

                document.head.appendChild(my_awesome_script);
                my_awesome_script.onload = () => {
                    this.loadScripts(scripts, index + 1, name);
                }



            }
            else {
                if (!script['src']) {

                    var evalCodeFn = eval(script['text']);
                    //    var my_awesome_script = document.createElement('script');
                    //    my_awesome_script.setAttribute("id",inpname)
                    //    my_awesome_script.text =script['text'];
                    //     document.head.appendChild(my_awesome_script);
                    //      my_awesome_script.onload= ()=>{
                    //         //  this.loadScripts(scripts,index+1,name);
                    //      }

                    if (typeof evalCodeFn == "function") {
                        evalCodeFn(this)
                    }
                }
                else
                    this.loadScripts(scripts, index + 1, name);
            }
        }
    },
    loader: function (status) {
        if (!document.getElementById("loader")) {
            var elemDiv = document.createElement('div');
            elemDiv.setAttribute("id", "loader");

            elemDiv.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto;  display: block;" width="70px" height="70px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" fill="none" stroke="#0971b3" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
              <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
            </circle>
            </svg>`;
            document.body.appendChild(elemDiv);
        }
        var loader = document.getElementById("loader");

        if (status) {
            loader.style.cssText = 'position:absolute; top:0; width:100%;height:40px;display:block;z-index:100;text-align:center';
        }
        else {
            loader.style.cssText = 'display:none;';
        }
    },

    ajax: function (...arguments) {
        arguments[0] = this.data.url + arguments[0];
        this.loader(true);
        return fetch(...arguments).then((data) => {
            this.loader(false);
            return data.json();
        }).catch(err => {
            this.loader(false);
            alert("Something went wrong " + err);
            console.log("err", err)
        });
    },
    mounted: function () {
        this.ajax("/get-settings").then((data) => {

            this.settings = data;
        })
    },
    el: function (selector) {
        this.data.el = selector;
    },
    elFilter: function (selector) {
        this.data.elFilter = selector;
    },
    getReport: function () {
        var response = '';
        if (this.data.visibility == 'Protected' && this.data.password == '') {
            this.data.password = prompt("Enter Password to access report");
        }
        var formData = new FormData();

        formData.append('reportId', this.data.reportId);
        formData.append('password', this.data.password);



        for (let param in this.data.parameters)
            formData.append('parameters[' + param + ']', this.data.parameters[param]);


        this.filters_html = '';
        this.ajax("/get-report", { method: "POST", body: formData }).then((data) => {
            this.data.response = data;
            this.handleReportData(data)
        })
    },
    getDashboardReport: function (listner) {
        this.dashboarChangeListner = listner
        var response = '';
        if (this.data.visibility == 'Protected' && this.data.password == '') {
            this.data.password = prompt("Enter Password to access report");
        }
        var formData = new FormData();

        formData.append('dashboardId', this.data.reportId);
        formData.append('password', this.data.password);







        this.filters_html = '';




        this.ajax("/get-dashboard", { method: "POST", body: formData }).then((data) => {
            this.data.response = data;
            this.handleDashboardData(this.data.response)
            this.applyDashboardFilter();


        })

    },
    applyDashboardFilter: function () {
        let elements = document.forms["report_filter"]
        const data = new FormData(elements);
        let params = '';
        this.data.parameters = {};
        for (const [name, value] of data) {
            if (value)
                this.data.parameters[name] = value;
            params += "name=" + value;
        }
        this.dashboarChangeListner(this.data.response, this.data.parameters)

    },

    getReportCustom: function () {
        var formData = new FormData();


        formData.append('sql', this.data.sql);
        formData.append('filters', JSON.stringify(this.data.vars));
        formData.append('layout', this.data.layout);
        formData.append('connection', this.data.connection);

        for (let param in this.data.parameters)
            formData.append('parameters[' + param + ']', this.data.parameters[param]);

        this.filters_html = '';
        this.ajax("/visualize-report", { method: "POST", body: formData }).then((data) => {
            this.data.response = data;
            this.handleReportData(data)
        })
    },



    setQueryParam: function () {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const entries = urlParams.entries();
        this.data.parameters = {};
        for (const entry of entries) {
            this.data.parameters[entry[0]] = entry[1];
        }
    },
    setReportId: function (id, visibility = 'Public') {
        this.data.reportId = id;
        this.data.visibility = visibility;
        this.setQueryParam()
        this.reportType = "REPORT";
        return this;
    },
    setDashboardId: function (id, visibility = 'Public') {
        this.data.reportId = id;
        this.data.visibility = visibility;
        this.setQueryParam()
        this.reportType = "DASHBOARD";
        return this;
    },
    setReportCustom: function (sql, vars, layout) {
        this.data.sql = sql;
        this.data.vars = vars;
        this.data.layout = layout;
        this.setQueryParam()
        return this;
    },
    handleSubmit: function () {
        let elements = document.forms["report_filter"]
        const data = new FormData(elements);
        let params = '';
        this.data.parameters = {};
        for (const [name, value] of data) {
            if (value)
                this.data.parameters[name] = value;
            params += "name=" + value;
        }
        console.log("this.data", this.data)
        if (this.data.reportId)
            this.getReport()
        else
            this.getReportCustom()



        return false;
    },
    handleDasboardSubmit: function () {
        let elements = document.forms["report_filter"]
        const data = new FormData(elements);
        let params = '';
        this.data.parameters = {};
        for (const [name, value] of data) {
            if (value)
                this.data.parameters[name] = value;
            params += "name=" + value;
        }

        this.getDashboardReport();




        return false;
    },

    handleReportData: async function (data) {

        var filterHtml = document.querySelector(this.data.elFilter);
        filterHtml.innerHTML = '';
        this.data.title = data.title || "";
        var title = `<h4>${this.data.title}`
        var form = `<h4>${this.data.title}</h4 > <form id="report_filter" onsubmit="return ReportBuilder.handleSubmit()"  >`;

        this.data.html = data['layout'].html;
        setTimeout(() => {
            this.loadScripts(data['layout'].scripts, 0, "main_html_script")
            this.loadStyle(data['layout'].styles, 0, 'main_html_style')
            let evnt = new CustomEvent("onReportUpdate", {
                response: data
            });
            document.dispatchEvent(evnt);
        }, 100)
        for (let name in data['inputs']) {
            form += data['inputs'][name].html;
        }


        form += '<div class="form-group"><button type="button" class="btn btn-primary"  onclick="ReportBuilder.handleSubmit()">Search</button></div></form>'

        if (!this.data.parameters['hide_filters'])
            filterHtml.insertAdjacentHTML('beforeend', form);
        else {
            filterHtml.insertAdjacentHTML('beforeend', title);
        }

        if (Object.keys(data['inputs']).length == 0 || Object.keys(data['inputs']).length <= document.querySelectorAll("#report_filter input[type='hidden']").length) {
            document.querySelector("#report_filter").style.display = 'none';
        }
        document.querySelector(this.data.el).innerHTML = this.data.html;

        for (let name in data['inputs']) {
            await this.loadPromisify(data, name);
        }



    },
    handleDashboardData: function (data) {

        var filterHtml = document.querySelector(this.data.elFilter);
        filterHtml.innerHTML = '';
        this.data.title = data.title || "";
        var title = `<h4>${this.data.title}</h4 >`
        var form = ` <form id="report_filter" onsubmit="return ReportBuilder.handleDasboardSubmit()"  >`;


        for (let name in data['inputs']) {
            form += data['inputs'][name].html;
            // document.querySelector( this.data.elFilter).innerHTML += data['inputs'][name].html;
            setTimeout(() => {
                this.loadScripts(data['inputs'][name].scripts, 0, data['inputs'][name]['input_type'].split(" ").join("_"))
                this.loadStyle(data['inputs'][name].styles, 0, data['inputs'][name]['input_type'].split(" ").join("_"))
            }, 100)
        }
        if (Object.keys(data['inputs']).length > 0) {

            form += '<div class="form-group"><button class="btn btn-success" type="button" onclick="ReportBuilder.applyDashboardFilter()">Search</button></div></form>'
        }
        if (!this.data.parameters['hide_filters'])
            filterHtml.insertAdjacentHTML('beforeend', form);




    },
    loadPromisify: function (data, name) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {

                this.loadScripts(data['inputs'][name].scripts, 0, data['inputs'][name]['input_type'].split(" ").join("_"), () => {
                    resolve();
                })
                this.loadStyle(data['inputs'][name].styles, 0, data['inputs'][name]['input_type'].split(" ").join("_"))
            }, 100)
        })
    }
}
// ReportBuilder.getReportCustom