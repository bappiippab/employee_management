<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                
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
                    paginate: {
                        last_page :0
                    }
                },
                page: 1,
                base_url: base_url
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
            viewCoupon: function(coupon){
                this.coupon = coupon;
                this.coupon.eligibleUsersArray = coupon.eligibleUsers.split(",");
            },
            deleteCoupons: function (coupon_id) {
                const swalWithBootstrapButtons = this.$swal.mixin({
                    confirmButtonClass: 'btn btn-success btn-flat',
                    cancelButtonClass: 'btn btn-danger btn-flat',
                    buttonsStyling: false,
                });

                swalWithBootstrapButtons({
                    title: 'Are you sure ?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        axios.delete(base_url+'/admin/coupons/'+coupon_id)
                            .then(response => {
                                var resp = response.data;
                                if(resp.status == 2003){
                                    this.$swal(resp.success.message,'','success');
                                    this.getCoupons(this.page);
                                    this.coupon = {
                                        ID: "",
                                        code: "",
                                        eligibleUsers: "",
                                        couponType: "",
                                        limit: "",
                                        maximumLimit: "",
                                        amountType: "",
                                        percentage: "",
                                        amount: "",
                                        generatedFor: "",
                                        generatorName: "",
                                        startedAt: "",
                                        expiredAt: "",
                                        eligibleUsersArray:[]
                                    };
                                }else{
                                    this.$swal(resp.error.message,"",'error');
                                }
                            });
                    }
                });
            },
            viewAppliedUsers: function (users) {
                this.applied_by_users = users;
                console.log(this.applied_by_users);
            }
        }
    }
</script>
