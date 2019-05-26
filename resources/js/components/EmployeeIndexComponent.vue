<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="box box-widget">
                    <div class="box-body table-responsive">
                        <form id="frm-create-employee" v-on:submit.prevent="saveEmployee">
                            <div class="form-group">
                                <label for="company">Company</label>
                                <select class="form-control" type="text" id="company" name="company" v-model="employee.company" required>
                                    <option value="">Please Select One</option>
                                    <option v-if="companies.company.length" v-for="company in companies.company" :value="company.id">{{company.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input class="form-control" type="text" id="first_name" name="first_name" v-model="employee.first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input class="form-control" type="text" id="last_name" name="last_name" v-model="employee.last_name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" v-model="employee.email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input class="form-control" type="text" id="phone" name="phone" v-model="employee.phone">
                            </div>
                            <div v-if="employee.id != ''" class="btn-group" role="group">
                                <button v-on:click="cancelEmployeeUpdate" type="button" class="btn btn-danger">Cancel</button>
                                <button type="submit" class="btn btn-warning">update</button>
                            </div>
                            <button v-if="employee.id == ''" type="submit" class="form-control btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                            <h3 class="box-title">Employees</h3>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="employees.employee.data.length" v-for="employee in employees.employee.data">
                                    <td>{{ employee.first_name }}</td>
                                    <td>{{ employee.last_name }}</td>
                                    <td>{{ employee.company_details.name }}</td>
                                    <td>{{ employee.email }}</td>
                                    <td>{{ employee.phone }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" v-on:click="editEmployee(employee.id)" class="btn btn-warning">Edit</button>
                                            <button type="button" v-on:click="deleteEmployee(employee.id)" class="btn btn-danger">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-right">
                        <paginate
                            v-model="page"
                            :page-count=employees.paginate.last_page
                            :click-handler="fetchEmployees"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'"
                            :page-class="'page-link'"
                            >
                        </paginate>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                companies: [],
                employees: {
                    employee :{
                        data: []
                    },
                    paginate: {
                        last_page :0
                    }
                },
                page: 1,
                base_url: base_url,
                employee: {
                    id: "",
                    first_name: "",
                    last_name: "",
                    company: "",
                    email: "",
                    phone: ""
                }
            };
        },
        ready() {
            this.fetchEmployees(1);
            this.fetchAllCompanies();
        },
        mounted() {
            this.fetchEmployees(1);
            this.fetchAllCompanies();
        },
        methods: {
            fetchAllCompanies: function() {
                axios.get(base_url+'/api/company?type=all')
                    .then(response => {
                        var resp = response.data;
                        if(resp.status = 2000){
                            this.companies = resp.payload;
                        }
                    });
            },
            fetchEmployees: function(page) {
                axios.get(base_url+'/api/employee?page=' + page)
                    .then(response => {
                        console.log(response);
                        var resp = response.data;
                        if(resp.status = 2000){
                            this.employees = resp.payload;
                        }
                    });
            },
            saveEmployee: function() {
                if(this.employee.id != ""){
                    axios.put(base_url+'/api/employee/'+this.employee.id, this.employee).then(response => {
                        var resp = response.data;
                        if(resp.status == 2002){
                            this.$swal(resp.success.message,'','success');
                            this.fetchEmployees(this.page);

                            this.employee.id = "";
                            this.employee.first_name = "";
                            this.employee.last_name = "";
                            this.employee.company = "";
                            this.employee.email = "";
                            this.employee.phone = "";
                        }else{
                            var error_string = "";
                            for (var i in resp.error.errors) {
                                error_string += "<span class='text-red'>"+resp.error.errors[i][0]+"</span><br>";
                            }
                            this.$swal(resp.error.message,error_string,'error');
                        }
                    });
                }else{
                    axios.post(base_url+'/api/employee', this.employee).then(response => {
                        var resp = response.data;
                        if(resp.status == 2001){
                            this.$swal(resp.success.message,'','success');
                            this.fetchEmployees(this.page);
                        }else{
                            var error_string = "";
                            for (var i in resp.error.errors) {
                                error_string += "<span class='text-red'>"+resp.error.errors[i][0]+"</span><br>";
                            }
                            this.$swal(resp.error.message,error_string,'error');
                        }
                    });
                }
            },
            editEmployee: function(employee_id) {
                axios.get(base_url+'/api/employee/'+employee_id+"/edit")
                .then(response => {
                        var resp = response.data;
                        if(resp.status = 2000){
                            this.employee.id = resp.payload.employee.id;
                            this.employee.first_name = resp.payload.employee.first_name;
                            this.employee.last_name = resp.payload.employee.last_name;
                            this.employee.company = resp.payload.employee.company;
                            this.employee.email = resp.payload.employee.email;
                            this.employee.phone = resp.payload.employee.phone;
                        }
                    });
            },
            cancelEmployeeUpdate: function() {
                this.employee.id = "";
                this.employee.first_name = "";
                this.employee.last_name = "";
                this.employee.company = "";
                this.employee.email = "";
                this.employee.phone = "";
            },
            deleteEmployee: function(employee_id) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.value) {
                        axios.delete(base_url+'/api/employee/'+employee_id)
                            .then(response => {
                                var resp = response.data;
                                if(resp.status == 2003){
                                    this.$swal(resp.success.message,'','success');
                                    this.fetchEmployees(this.page);
                                }else{
                                    this.$swal(resp.error.message,"",'error');
                                }
                            });
                    }
                })
            }
        }
    }
</script>
