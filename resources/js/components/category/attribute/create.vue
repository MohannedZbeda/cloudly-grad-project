<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-card style="min-width : 50%" class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{
                        $translate("Register a Attribute", "إضافة خاصية")
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            :label="$translate('AR Name', 'الإسم بالعربي')"
                            :placeholder="
                                $translate(
                                    'AR Attribute Name',
                                    'إسم الخاصية بالعربي'
                                )
                            "
                            outlined
                            v-model="form.ar_name"
                        ></v-text-field>

                        <v-text-field
                            dir="ltr"
                            :label="$translate('EN Name', 'الإسم بالإنجليزي')"
                            :placeholder="
                                $translate(
                                    'EN Attribute Name',
                                    'إسم الخاصية بالإنجليزي'
                                )
                            "
                            outlined
                            v-model="form.en_name"
                        ></v-text-field>

                        <v-checkbox
                            v-model="form.advanced"
                            :label="
                                $translate(
                                    'Advanced Attribute ? ',
                                    'خاصية متقدمة ؟'
                                )
                            "
                        ></v-checkbox>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="create">{{
                        $translate("Register Attribute", "إضافة خاصية")
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import CategoryService from "../../../services/Category";
export default {
    name: "category.create",
    data() {
        return {
            category_id: this.$route.params.category_id,
            form: {
                ar_name: "",
                en_name: "",
                advanced: false
            }
        };
    },
    methods: {
        create() {
            const payload = {
                category_id: this.category_id,
                ...this.form
            };
            CategoryService.AddAttribute(payload).then(() => {
                this.$swal(
                    this.$translate(
                        "Operation done successfully !",
                        "تمت العملية بنجاح !"
                    ),
                    this.$translate(
                        "Attribute registered successfully",
                        "تمت إضافة الخاصية بنجاح"
                    ),
                    "success"
                ).then(() => {
                    this.$router.push(
                        "/categories/" + this.category_id + "/attributes"
                    );
                });
            });
        }
    }
};
</script>
