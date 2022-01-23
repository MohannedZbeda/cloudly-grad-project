<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-card style="min-width : 50%" class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{
                        $translate("Update a custom  attribute", "تعديل خاصية ")
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            :label="$translate('Custom Price', 'سعر الوحدة')"
                            :placeholder="
                                $translate(
                                    'Custom price per unit',
                                    'سعر الوحدة'
                                )
                            "
                            outlined
                            type="number"
                            v-model="form.custom_price"
                        ></v-text-field>
                        <v-text-field
                            :label="
                                $translate('Unit Maximum', 'أقصى قيمة للوحدة')
                            "
                            :placeholder="
                                $translate(
                                    'Maximum adjustment per unit',
                                    'أقصى قيمة يسمح بإضافتها لهذه الخاصية'
                                )
                            "
                            outlined
                            type="number"
                            v-model="form.unit_max"
                        ></v-text-field>

                        <v-text-field
                            :label="
                                $translate('Unit Minimum', 'أدنى قيمة للوحدة')
                            "
                            :placeholder="
                                $translate(
                                    'Minimum adjustment per unit',
                                    'أدنى قيمة يسمح بإضافتها لهذه الخاصية'
                                )
                            "
                            outlined
                            type="number"
                            v-model="form.unit_min"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="update">{{
                        $translate("Update Variant", "تعديل منتج")
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import ProductService from "../../services/Product";
export default {
    name: "custom.edit",
    data() {
        return {
            product_id: this.$route.params.product_id,
            id: this.$route.params.id,
            form: {
                custom_price: null,
                unit_max: null,
                unit_min: null
            }
        };
    },
    beforeMount() {
        ProductService.GetCustomAttribute(this.id).then(response => {
            this.form.custom_price = response.data.attribute.custom_price;
            this.form.unit_max = response.data.attribute.unit_max;
            this.form.unit_min = response.data.attribute.unit_min;
        });
    },
    methods: {
        update() {
            ProductService.UpdateCustomAttribute({
                id: this.id,
                ...this.form
            }).then(() => {
                this.$swal(
                    this.$translate(
                        "Operation done successfully !",
                        "تمت العملية بنجاح !"
                    ),
                    this.$translate(
                        "Attribute updated successfully",
                        "تمت تعديل الخاصية بنجاح"
                    ),
                    "success"
                ).then(() => {
                    this.$router.push(`/products/${this.product_id}/variants`);
                });
            });
        }
    }
};
</script>

