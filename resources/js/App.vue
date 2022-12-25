<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" app :right="getDir()">
            <v-list-item>
                <v-list-item-content
                    style="display: flex; align-items: center; padding : 1.8em"
                >
                    
                    <h3 style="text-align : center; padding : .6em 0">
                        <b>{{ user.name }}</b>
                    </h3>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>

            <v-list>
                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style=""
                                    >mdi-account-supervisor</v-icon
                                >
                                {{
                                    $translate("Admins", "المسؤولين")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/admins" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-account-details</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("All Admins", "جميع المسؤولين")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <router-link to="/admins/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-account-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add Admin", "إضافة مسؤول")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                </v-list-group>

                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style=""
                                    >mdi-account-tie</v-icon
                                >
                                {{
                                    $translate("Customers", "الزبائن")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/customers" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-account-details</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("All Customers", "جميع الزبائن")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <!-- <router-link to="/customers/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-account-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add Customer", "إضافة زبون")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link> -->
                </v-list-group>

                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style="margin : 10dp"
                                    >mdi-solar-panel</v-icon
                                >{{
                                    $translate("Categories", "الفئات")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/categories" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-format-list-bulleted</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate(
                                        "All Categories",
                                        "جميع الفئات"
                                    )
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <router-link to="/categories/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add a Category", "إضافة فئة")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                </v-list-group>

                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style="margin : 10dp"
                                    >mdi-archive</v-icon
                                >{{
                                    $translate("Products", "المنتجات")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/products" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-format-list-bulleted</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("All Products", "جميع المنتجات")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <router-link to="/products/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add a Product", "إضافة منتج")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                </v-list-group>

                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style="margin : 10dp">mdi-gift</v-icon
                                >{{
                                    $translate("Packages", "الباقات")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/packages" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-format-list-bulleted</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("All Packages", "كل الباقات")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <router-link to="/packages/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add a package", "إضافة باقة")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                </v-list-group>

                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style="margin : 10dp"
                                    >mdi-autorenew</v-icon
                                >{{
                                    $translate("Payment Cycles", "دورات الدفع")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/cycles" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-format-list-bulleted</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("All Cycles", "كل الدورات")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <router-link to="/cycles/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add a Cycle", "إضافة دورة")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                </v-list-group>

                <v-list-group no-action>
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title
                                ><v-icon style="margin : 10dp"
                                    >mdi-frequently-asked-questions</v-icon
                                >{{
                                    $translate("FAQs", "الأسئلة الشائعة")
                                }}</v-list-item-title
                            >
                        </v-list-item-content>
                    </template>

                    <router-link to="/faqs" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-format-list-bulleted</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("All FAQs", "كل الأسئلة")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>

                    <router-link to="/faqs/create" class="link">
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-plus</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>{{
                                    $translate("Add Question", "إضافة سؤال")
                                }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </router-link>
                </v-list-group>

            </v-list>
            <v-list dense>
                <v-list-item
                    link
                    @click="
                        drawer = false;
                        $store.commit('logout');
                    "
                >
                    <v-list-item-action>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{
                            $translate("Logout", "تسجيل الخروج")
                        }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app color="primary" dark>
            <v-app-bar-nav-icon
                @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <h4 style="padding : 0 1.2em">
                {{
                    $translate(
                        "Datacenter - Dashboard",
                        "مركز البيانات - لوحة التحكم"
                    )
                }}
            </h4>
            <v-spacer></v-spacer>

            <v-btn
                class="mx-2"
                small
                outlined
                color="white"
                @click="changeLang()"
            >
                <b style="padding : 0 .5em">{{
                    $translate("العربية", "English")
                }}</b>
                <v-icon small>
                    mdi-translate
                </v-icon>
            </v-btn>
        </v-app-bar>

        <v-main>
            <router-view></router-view>
        </v-main>
        <v-footer color="primary" app>
            <span class="white--text">&copy; CLOUDLY</span>
        </v-footer>
    </v-app>
</template>

<script>
import AdminService from "./services/Admin";
export default {
    name: "App",
    data() {
        return {
            drawer: false,
            user: {}
        };
    },
    async mounted() {
        this.user = await AdminService.GetAuthUser();
    },
    methods: {
        changeLang() {
            this.$store.commit("changeLang");
            this.$vuetify.rtl = this.$store.state.language == "ar";
        },
        getDir() {
            return this.$store.state.language == "ar";
        }
    }
};
</script>
<style lang="css">
@import url("https://fonts.googleapis.com/css2?family=Tajawal:wght@300&display=swap");

* {
    font-family: "Tajawal", sans-serif;
}
.link {
    text-decoration: none;
}
</style>
