export default {
    methods: {
        //ApiCalls
        getUsers(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/users/search',
                data: data
            }).then(response => {
                if (response.data.status == 1) {
                    return callBackHandler('200', response.data);
                } else {
                    return callBackHandler('001', response.data.errors);
                }
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
        createUserCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/users/store-user',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        updateUserCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/users/update-user',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        updateProfileCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/users/update-profile',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        removeRoleCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/users/remove-role',
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        changeUserPasswordCall(id, data, callBackHandler) {
            axios({
                method: 'put',
                url: '/api/admin/users/change-user-password/' + id,
                data: data
            }).then(response => {
                return callBackHandler(response);
            }).catch((error) => {
                return callBackHandler(error);
            });
        },
        getLedgersCall(data, callBackHandler) {
            axios({
                method: 'post',
                url: '/api/admin/users/ledgers/search',
                data: data
            }).then(response => {
                return callBackHandler(response.data);
            }).catch((error) => {
                return callBackHandler('002', error);
            });
        },
    }
}