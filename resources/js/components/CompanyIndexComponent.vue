<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="box box-widget">
                    <div class="box-body table-responsive">
                        <form id="frm-create-company" v-on:submit.prevent="saveCompany">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" type="text" id="name" name="name" v-model="company.name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="text" id="email" name="email" v-model="company.email">
                            </div>
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input class="form-control" type="text" id="website" name="website" v-model="company.website">
                            </div>

                            <button class="form-control btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <div class="col-md-12">
                            <h3 class="box-title">Companies</h3>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Logo</th>
                                    <th>Website</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="companies.company.data.length" v-for="company in companies.company.data">
                                    <td>{{ company.name }}</td>
                                    <td>{{ company.email }}</td>
                                    <td>{{ company.logo }}</td>
                                    <td>{{ company.website }}</td>
                                    <td>
                                        button
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-right">
                        <paginate
                            v-model="page"
                            :page-count=companies.paginate.last_page
                            :click-handler="fetchCompanies"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'"
                            :page-class="'page-item'"
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
                companies: {
                    company :{
                        data: []
                    },
                    paginate: {
                        last_page :0
                    }
                },
                page: 1,
                base_url: base_url,
                company: {
                    name: "",
                    email: "",
                    logo: "",
                    website: ""
                }
            };
        },
        ready() {
            this.fetchCompanies(1);
        },
        mounted() {
            this.fetchCompanies(1);
        },
        methods: {
            fetchCompanies: function(page) {
                axios.get(base_url+'/api/company?page=' + page)
                    .then(response => {
                        var resp = response.data;
                        if(resp.status = 2000){
                            this.companies = resp.payload;
                        }
                    });

                console.log(this.companies);
            },
            saveCompany: function() {
                axios.post(base_url+'/api/company', this.company).then(response => {
                    var resp = response.data;
                    if(resp.status == 2001){
                        this.$swal(resp.success.message,'','success');
                    }else{
                        var error_string = "";
                        for (var i in resp.error.errors) {
                            error_string += "<span class='text-red'>"+resp.error.errors[i][0]+"</span><br>";
                        }
                        this.$swal(resp.error.message,error_string,'error');
                    }
                });
            }
        }
    }
</script>
