<template>
    <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="col-md-12">
                        <h4 style="margin-bottom:10px;"><strong>Users</strong></h4>
                        </div>
                        <div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-12 col-md-6 " style="padding-left:21px; padding-right:21px;">
                                                <label>Search</label>
                                                <input type="text" v-model="query" class="form-control" id="query" placeholder="Name, Phone & Email">
                                            </div>
                                            <div class="col-12 col-md-3 " style="padding-left:21px; padding-right:21px;">
                                                <div class="form-group" style="width: 100%;">
                                                    <label>Roles</label>
                                                    <select id="select2-roles" class="select2-placeholder form-control" data-placeholder="Select roles..." style="width: 100%">
                                                        <option value="all">All</option>
                                                        <option v-for="(role, index) in roles" :key="index" :value="role.name" style="text-transform:capitalize;">{{capitalizeFirstLetter(role.name)}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div v-if="users.length >0" class="table-responsive">
                                        <table id="recent-customers" class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Full Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Audit</th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(user, index) in users" :key="index">
                                                    <td class="text-truncate">{{user.firstname}} {{user.lastname}}</td>
                                                    <td class="text-truncate">{{user.phone}}</td>
                                                    <td class="text-truncate">{{user.email}}</td>
                                                    <td class="text-center">
                                                        <span :class="{'badge badge-primary' : user.status == 'active',
                                                        'badge badge-danger' : user.status == 'inactive'}">{{user.status}}
                                                        </span>
                                                    </td>
                                                    <td style="text-align:center;"><a :href="'/api/admin/su/'+user.id"><i class="fa fa-eye text-navy"></i></a></td>
                                                    <td style="text-align:center;"><a :href="'/admin/users/edit/' + user.id" class="btn btn-secondary"><i class="fa fa-pencil"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="text-center">
                                        <div style="padding: 20px"><h4>{{no_found_msg}}</h4></div>
                                    </div>
                                </div>
                                <order-paginator v-if="paginator.last_page > 1" :pagination="paginator" @paginate="searchUsers()" :offset="offset"></order-paginator>
                            </div>
                        </div>
                </div>
        </div>
    </div>
</template>

<script>
    import UserServices from '../services/UserServices.js';
    import Paginator from '../../../../../components/Paginator.vue';

    export default {
        mixins:[UserServices],
        components: {
            'order-paginator': Paginator
        },
        props:['roles'],
        data() {
            return {
                users: [],
                no_found_msg: 'Users not found',
                query:'',
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10,
                role:'all'
            }
        },
        watch:{
            query:_.debounce(function(){
                this.searchUsers(1);
                },300),
            role:_.debounce(function(){
                this.searchUsers(1);
                },300)
        },
        methods: {
            getUsersCallback(code, response, errors) {
                if (code == '200') {
                    if(response.data.exported){
                        swal("Success!", "The report is being exported and will be sent to your email.", "success");
                    }else{
                        if(response.data.length > 0){
                            this.paginator = response.pagination;
                        }else{
                            this.paginator= {
                                total: 0,
                                per_page: 2,
                                from: 1,
                                to: 0,
                                current_page: 1
                            };
                        }
                        this.users = response.data;
                    }
                }else{
                    swal("Error!", errors[0], "error");
                }
                $.LoadingOverlay("hide");
            },
            print(){
                printJS({ printable: 'recent-orders', type: 'html', header: 'Sales by Date Range ('+ this.start_date + '-'+ this.end_date +')'});
            },
            searchUsers(page,exported = false){
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: this.offset,
                    fields: ['id', 'firstname', 'lastname', 'phone', 'email', 'users', 'status'],
                };

                if (this.query != '') {
                    let query = this.query;
                    params['query'] = {
                        value: "+*" + query.replace(/\s+/g, "* +*") + "*", // search term
                        fields: ['firstname', 'lastname', 'phone', 'email', 'status']
                    }
                }
                if(exported){
                    params['export'] = exported;
                }

                if(this.role != '' && this.role != 'all'){
                    params['with'] = {
                        user_roles:{
                            where:[
                                ['name', this.role]
                            ]
                        }
                    }
                }

                this.getUsers(params, this.getUsersCallback);
            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        },
        created() {
            $.LoadingOverlay("show");
            this.getUsers({
                page: this.paginator.current_page,
                limit: this.offset,
                fields: ['id', 'firstname', 'lastname', 'phone', 'email', 'users', 'status'],
            }, this.getUsersCallback);
        },
        mounted() {
            var _this = this;
            this.$nextTick(function(){
                $("#select2-roles").select2();
                $("#select2-roles").on('change', function (e) {
                    _this.role = $("#select2-roles").val()
                });
            })
        }
    }
</script>
<style>
    .icon-store {
        width: 30px;
        height: auto;
    }

    .icon-amazon {
        width: 50px;
        height: auto;
    }

    .input-error-select {
        color: #d61212 !important;
        border: 1px solid #b60707 !important;
        border-radius: 5px
    }

    .message-error {
        color: #d61212;
        float: right;
        padding-top: 10px;
        font-size: 12px;
    }
    .icons-custom{
        width:20px;
        height:20px;
    }
</style>