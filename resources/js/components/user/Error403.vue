<template>
    <div>
        <div class="row page-titles">
            <div class="col-12 col-md-6 p-0">
                <h4>Hello {{user.user_id}},<span>Welcome here</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4>Access Forbidden</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
        mounted() {
            localStorage.clear();
            this.$router.push({ path: '/login'});
        }
    }
</script>