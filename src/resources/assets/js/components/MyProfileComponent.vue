<template>
    <div>
        <div id="user-card" class="card" style="min-height: 70vh;">
            <div class="card-content">
                <div v-if="!account" class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="form-section">My Profile</h4> 
                        </div>
                    </div>
                    <a class="heading-elements-toggle">
                        <i class="fa fa-ellipsis-v font-medium-3"></i>
                    </a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li>
                                <a data-action="collapse">
                                    <i class="ft-minus"></i>
                                </a>
                            </li>
                            <li>
                                <a data-action="reload">
                                    <i class="ft-rotate-cw"></i>
                                </a>
                            </li>
                            <li>
                                <a data-action="expand">
                                    <i class="ft-maximize"></i>
                                </a>
                            </li>
                            <li>
                                <a data-action="close">
                                    <i class="ft-x"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body" style="padding-top:0px;">
                    <ul class="nav nav-tabs mt-1">
                        <li class="nav-item">
                            <a class="nav-link active" id="base-general" data-toggle="tab" aria-controls="tab1"
                                href="#general" aria-expanded="true"><i class="fa fa-home"></i>General</a>
                        </li>
                        <li v-if="!account" class="nav-item">
                            <a class="nav-link" id="base-ledgers" data-toggle="tab" aria-controls="tab2" @click="getLedgers()"
                                href="#_ledgers" aria-expanded="false"><i class="fa fa-file-o"></i>My Ledgers</a>
                        </li>
                    </ul>
                     <div class="tab-content px-1 pt-1">
                        <div role="tabpanel" class="tab-pane active" id="general" aria-expanded="true" aria-labelledby="base-general">
                            <form class="form row card-body">
                                <div class="form-group mb-1 col-sm-6 col-md-3">
                                    <label for="firstname">Firstname</label>
                                    <br>
                                    <input v-model="user.firstname" type="text" class="form-control" id="firstname">
                                    <span v-if="error.firstname" class="message-error">{{error_message.firstname}}</span>
                                </div>
                                <div class="form-group mb-1 col-sm-6 col-md-3">
                                    <label for="lastname">Lastname</label>
                                    <br>
                                    <input v-model="user.lastname" type="text" class="form-control" id="lastname">
                                    <span v-if="error.lastname" class="message-error">{{error_message.lastname}}</span>
                                </div>
                                <div class="form-group mb-1 col-sm-6 col-md-3">
                                    <label for="apt">Email</label>
                                    <br>
                                    <input v-model="user.email" type="text" class="form-control" id="email">
                                    <span v-if="error.email" class="message-error">{{error_message.email}}</span>
                                </div>
                                <div class="form-group mb-1 col-sm-6 col-md-3">
                                    <label for="city">Phone</label>
                                    <br>
                                    <input v-model="user.phone" type="text" class="form-control" id="phone">
                                </div>
                            </form>
                            <div class="row card-body">
                                <div class="form-group mb-1 col-sm-12 col-md-12" style="width: 100%">
                                    <div class="form-group text-right" style="margin-top: 25px">
                                        <button  type="button"
                                            class="btn btn-primary" data-toggle="modal" data-target="#changePassword">Set new password
                                        </button>
                                        <button @click="createUser" type="button"
                                            class="btn btn-primary">Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="_ledgers" aria-expanded="true" aria-labelledby="base-ledgers">
                            <div class="row" style="margin-top:20px;">
                                <div class="col-12 col-md-6">
                                    <strong>Pending Balance: </strong> {{pending_balance}}
                                </div>
                                <div class="col-12 col-md-6">
                                    <strong>Available Balance: </strong> {{available_balance}}
                                </div>
                            </div>
                            <div v-if="current_ledgers.length > 0" style="margin-top:40px;">
                                <ul class="list-group">
                                    <li id="first" class="list-group-item d-none d-lg-block shrink">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <strong>Name</strong>
                                            </div>
                                            <div class="col-md-1">
                                                <strong>Type</strong>
                                            </div>
                                            <div class="col-md-1">
                                                <strong>Debit</strong>
                                            </div>
                                            <div class="col-md-1">
                                                <strong>Credit</strong>
                                            </div>
                                            <!--  <div class="col-md-1">
                                                        <strong>Date of birth</strong>
                                                    </div> -->
                                            <div class="col-md-2">
                                                <strong>Start Balance</strong>
                                            </div>
                                            <div class="col-md-2">
                                                <strong>End Balance</strong>
                                            </div>
                                            <div class="col-md-1">
                                                <strong>Status</strong>
                                            </div>
                                            <div class="col-md-2">
                                                <strong>Created at</strong>
                                            </div>
                                        </div>
                                    </li>
                                    <li v-for="(ledgers, index) in current_ledgers" :key="index"
                                        class="list-group-item shrink">
                                        <div class="row">
                                            <div class="col-md-2" style="padding-top: 10px">
                                                <span>
                                                    {{ledgers.user.firstname}} {{ledgers.user.lastname}}
                                                </span>
                                            </div>
                                            <div class="col-md-1" style="padding-top: 10px">
                                                <span>{{ledgers.type}}</span>
                                            </div>
                                            <div class="col-md-1" style="padding-top: 10px">
                                                <span>
                                                    ${{parseFloat(ledgers.debit).toFixed(2)}}
                                                </span>
                                            </div>
                                            <div class="col-md-1" style="padding-top: 10px">
                                                    ${{parseFloat(ledgers.credit).toFixed(2)}}
                                            </div>
                                            <div class="col-md-2" style="padding-top: 10px">
                                                    ${{parseFloat(ledgers.sbalance).toFixed(2)}}
                                            </div>
                                            <div class="col-md-2" style="padding-top: 10px">
                                                    ${{parseFloat(ledgers.ebalance).toFixed(2)}}
                                            </div>
                                            <div class="col-md-1" style="padding-top: 10px">
                                                    {{ledgers.status}}
                                            </div>
                                            <div class="col-md-2" style="padding-top: 10px">
                                                {{moment(ledgers.created_at).format('LLL')}}
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div style="margin-top:20px;">
                                    <paginator v-if="paginator.total > paginator.per_page" :pagination="paginator" @paginate="searchPayments()" :offset="offset"></paginator>
                                </div>
                            </div>
                            <div v-else class="text-center" style="margin-top: 50px">
                                <h3>No ledgers registered at this moment</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change user password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <label for="basicInput">Password</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ft-type"></i>
                                </span>
                                <input v-model="new_password" class="form-control" :class="{'input-error-select' : error.new_password}"
                                    type="password">
                            </div>
                            <span v-if="error.new_password" class="message-error">{{error_message.new_password}}</span>
                        </fieldset>
                        <fieldset style="margin-top: 15px">
                            <label for="basicInput">Confirm password</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ft-type"></i>
                                </span>
                                <input v-model="new_password_confirmation" class="form-control" :class="{'input-error-select' : error.new_password_confirmation}"
                                    type="password">
                            </div>
                            <span v-if="error.new_password_confirmation" class="message-error">{{error_message.new_password_confirmation}}</span>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="changeUserPassword()" class="btn btn-primary">Save</button>
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
        props: ['editable_user','user_id','pending_balance','available_balance','account'],
        mixins: [UserServices],
        components:{
             'paginator': Paginator,
        },
        data() {
            return {
                moment:moment,
                user: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    phone: '',
                },
                new_password: '',
                new_password_confirmation: '',
                error: {
                    firstname: false,
                    lastname: false,
                    email: false,
                    new_password: false
                },
                error_message: {
                    firstname: '',
                    lastname: '',
                    email: '',
                    new_password: ''
                },
                current_ledgers:[],
                paginator: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 10,
            }
        },

        methods: {
            createUser() {
                if (this.user.firstname && this.user.lastname && this.user.email) {
                    if (Helpers.validateEmail(this.user.email)) {
                        $.LoadingOverlay("show");
                        this.updateProfileCall(this.user, this.createProfileCallback)
                    } else {
                        this.error.email = true;
                        this.error_message.email = 'Email is invalid';
                    }
                } else {
                    if (this.user.firstname == '') {
                        this.error.firstname = true;
                        this.error_message.firstname = 'Firstname is required';
                    }
                    if (this.user.lastname == '') {
                        this.error.lastname = true;
                        this.error_message.lastname = 'Lastname is required';
                    }
                    if (this.user.email == '') {
                        this.error.email = true;
                        this.error_message.email = 'Email is required';
                    }
                }
            },
            createProfileCallback(response) {
                $.LoadingOverlay("hide");
                if (response.data.status == 1) {
                    toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                } else {
                    if (Helpers.isAssoc(response.data.errors)) {
                        let errors = [];
                        for (var i in response.data.errors) {
                            var string
                            errors.push('<span>' + response.data.errors[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.data.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            changeUserPassword() {
                if (this.new_password && this.new_password_confirmation) {
                    let data = {
                        'new_password': this.new_password,
                        'new_password_confirmation': this.new_password_confirmation
                    }
                    $.LoadingOverlay("show");
                    this.changeUserPasswordCall(this.user.id, data, this.changeUserPasswordCallback);
                } else {
                    if (!this.new_password) {
                        this.error.new_password = true;
                        this.error_message.new_password = "Password is required";
                    }
                    if (!this.new_password_confirmation) {
                        this.error.new_password_confirmation = true;
                        this.error_message.new_password_confirmation = "Password confirmation is required";
                    }
                }
            },
            changeUserPasswordCallback(response) {
                $.LoadingOverlay("hide");
                if (response.data.status == 1) {
                    $('#changePassword').modal('hide');
                    toastr.success(response.data.message, 'Success', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                } else {
                    if (Helpers.isAssoc(response.data.errors)) {
                        let errors = [];
                        for (var i in response.data.errors) {
                            var string
                            errors.push('<span>' + response.data.errors[i] + '</span></br>')
                        }
                        toastr.error(errors, 'Some error(s) has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    } else {
                        toastr.error(response.data.errors[0], 'An error has occurred', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                    }
                }
            },
            getLedgers(page){
                $.LoadingOverlay("show");
                var params = {
                    page: (typeof page == 'undefined') ? this.paginator.current_page : page,
                    limit: this.offset,
                    fields: ['id','user_id', 'type', 'debit','credit', 'sbalance', 'ebalance','status','created_at'],
                    orderby:{
                        id: 'DESC'
                    },
                    with:['user'],
                    where:[
                        ['user_id', this.user_id]
                    ]
                };
                this.getLedgersCall(params, this.getLedgersCallback);
            },
            getLedgersCallback(response){
                console.log(response);
                if(response.status > 0){
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
                    this.current_ledgers= response.data;
                }else{
                    swal("Error!", response.errors[0], "error");
                }
                 $.LoadingOverlay("hide");
            },
        },
        watch: {
            'user.firstname'(val) {
                if (val) {
                    this.error.firstname = false;
                    this.error_message.firstname = '';
                }
            },
            'user.lastname'(val) {
                if (val) {
                    this.error.lastname = false;
                    this.error_message.lastname = '';
                }
            },
            'user.email'(val) {
                if (val) {
                    this.error.email = false;
                    this.error_message.email = '';
                }
            },
            new_password(val) {
                if (val) {
                    this.error.new_password = false;
                    this.error_message.new_password = '';
                }
            },
            new_password_confirmation(val) {
                if (val) {
                    this.error.new_password_confirmation = false;
                    this.error_message.new_password_confirmation = '';
                }
            }
        },
        filters: {
            capitalize: function (value) {
                if (!value) return ''
                value = value.toString()
                return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },
        created() {
            if (this.editable_user) {
                this.user = Object.assign(this.user, this.editable_user);
            }
        },
        mounted() {
            var self = this;
            this.$nextTick(function () {
                self.new_password = "";

                if(self.account){
                    $('#user-card').removeClass('card');
                }
            });

            $('#changePassword').on('hidden.bs.modal', function (e) {
                self.new_password = "";
                self.new_password_confirmation = "";
            })
        }
    }
</script>
<style>
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

    label {
        font-size: 12px
    }

    .select2-selection__rendered {
        font-size: 12px !important;
    }

    .vcenter {
        display: inline-block;
        vertical-align: middle;
        float: none;
    }
</style>
<style scoped>
    input,
    select,
    textarea {
        font-size: 12px;
    }

    .logo-list {
        width: 80px;
        height: auto;
    }
    #first {
        background-color: #e0e2e5;
        font-size: 12px;
    }

    .shrink {
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>