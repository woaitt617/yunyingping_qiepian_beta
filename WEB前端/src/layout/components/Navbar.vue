<template>
    <div class="navbar">
        <hamburger
            id="hamburger-container"
            :is-active="sidebar.opened"
            class="hamburger-container"
            @toggleClick="toggleSideBar"
        />

        <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />

        <div class="right-menu">
            <template v-if="device !== 'mobile'">
                <screenfull
                    id="screenfull"
                    class="right-menu-item hover-effect"
                />

                <el-tooltip content="字体大小" effect="dark" placement="bottom">
                    <size-select
                        id="size-select"
                        class="right-menu-item hover-effect"
                    />
                </el-tooltip>
            </template>

            <el-dropdown
                class="avatar-container right-menu-item hover-effect"
                trigger="click"
            >
                <div class="avatar-wrapper">
                    <img :src="avatar" class="user-avatar" />
                    <i class="el-icon-caret-bottom" />
                </div>
                <el-dropdown-menu slot="dropdown">
                    <!-- <router-link to="/profile/index">
            <el-dropdown-item>Profile</el-dropdown-item>
          </router-link> -->
                    <a target="_blank" :href="siteHelp">
                        <el-dropdown-item>使用帮助</el-dropdown-item>
                    </a>
                    <el-dropdown-item divided @click.native="logout">
                        <span style="display:block;">退出</span>
                    </el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Breadcrumb from '@/components/Breadcrumb';
import Hamburger from '@/components/Hamburger';
import Screenfull from '@/components/Screenfull';
import SizeSelect from '@/components/SizeSelect';
import Utils from '@/utils/utils';
export default {
    components: {
        Breadcrumb,
        Hamburger,
        Screenfull,
        SizeSelect
    },
    data() {
        return {
            avatar: '',
            siteHelp: process.env.VUE_APP_BASE_SITE_HELP
        };
    },
    computed: {
        ...mapGetters(['sidebar', 'device'])
    },
    mounted() {
        let userinfo = Utils.getStorage('userInfo');
        this.avatar = 'http://q1.qlogo.cn/g?b=qq&nk=' + userinfo.qq + '&s=640';
    },
    methods: {
        toggleSideBar() {
            this.$store.dispatch('app/toggleSideBar');
        },
        async logout() {
            await this.$store.dispatch('user/logout');
            Utils.removeStorage('userInfo');
            this.$router.push(`/login?redirect=${this.$route.fullPath}`);
        }
    }
};
</script>

<style lang="scss" scoped>
.navbar {
    height: 50px;
    overflow: hidden;
    position: relative;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);

    .hamburger-container {
        line-height: 46px;
        height: 100%;
        float: left;
        cursor: pointer;
        transition: background 0.3s;
        -webkit-tap-highlight-color: transparent;

        &:hover {
            background: rgba(0, 0, 0, 0.025);
        }
    }

    .breadcrumb-container {
        float: left;
    }

    .errLog-container {
        display: inline-block;
        vertical-align: top;
    }

    .right-menu {
        float: right;
        height: 100%;
        line-height: 50px;

        &:focus {
            outline: none;
        }

        .right-menu-item {
            display: inline-block;
            padding: 0 8px;
            height: 100%;
            font-size: 18px;
            color: #5a5e66;
            vertical-align: text-bottom;

            &.hover-effect {
                cursor: pointer;
                transition: background 0.3s;

                &:hover {
                    background: rgba(0, 0, 0, 0.025);
                }
            }
        }

        .avatar-container {
            margin-right: 30px;

            .avatar-wrapper {
                margin-top: 5px;
                position: relative;

                .user-avatar {
                    cursor: pointer;
                    width: 40px;
                    height: 40px;
                    border-radius: 10px;
                }

                .el-icon-caret-bottom {
                    cursor: pointer;
                    position: absolute;
                    right: -20px;
                    top: 25px;
                    font-size: 12px;
                }
            }
        }
    }
}
</style>
