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
                            :label="$translate('Name', 'الإسم')"
                            :placeholder="
                                $translate(
                                    'Attribute Name',
                                    'إسم الخاصية'
                                )
                            "
                            outlined
                            v-model="form.name"
                            :error-messages="errors.name ? $translate(errors.name[0].en, errors.name[0].ar) : null"
                        ></v-text-field>

                        <v-checkbox
                            v-model="form.advanced"
                            :label="
                                $translate(
                                    'Advanced Attribute ? ',
                                    'خاصية غير أساسية ؟'
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
                name: "",
                advanced: false
            },
            errors: []
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
            }).catch(errors => {
              this.errors = errors.response.data.errors;
          });
        }
    }
};
</script>
