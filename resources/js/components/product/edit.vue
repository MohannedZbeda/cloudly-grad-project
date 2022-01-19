<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-card style="min-width : 50%" class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{
                        $translate("Update a Product", "تعديل منتج")
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <p v-if="noAttributes" style="color:red">
                        {{
                            $translate(
                                "There are no attributes for this product, Please add some",
                                "لا توجد خصائص لهذا المنتج، يرجى الإضافة"
                            )
                        }}
                    </p>
                    <v-form>
                        <v-select
                            :items="categories"
                            item-text="en_name"
                            item-value="id"
                            :label="
                                $translate('Product Category', 'تصنيف المنتج')
                            "
                            v-model="form.category_id"
                            outlined
                        ></v-select>
                        <v-text-field
                            :label="$translate('AR Name', 'الإسم بالعربي')"
                            :placeholder="
                                $translate(
                                    'AR Product Name',
                                    'إسم المنتج بالعربي'
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
                                    'EN Product Name',
                                    'إسم المنتج بالإنجليزي'
                                )
                            "
                            outlined
                            v-model="form.en_name"
                        ></v-text-field>
                        <br />
                        <b>
                            {{
                                $translate(
                                    "If chekced, The Customer will be able to customize this broduct to their needs, Limited only by the attribute limits",
                                    "في حالة إختيار هذا الخيار, سيتمكن الزبون من تعديل المنتج لما يناسبه من المواصفات ضمن الحدود المسموحة"
                                )
                            }}
                        </b>
                        <br />
                        <v-checkbox
                            v-model="form.customizable"
                            :label="
                                $translate('Customizable ? ', 'قابل للتعديل ؟')
                            "
                        ></v-checkbox>
                        <br />
                        <v-divider style="background-color: black"></v-divider>
                        <br />
                        <div v-if="form.customizable">
                            <v-text-field
                                :label="
                                    $translate('Custom Price', 'سعر الوحدة')
                                "
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
                                    $translate(
                                        'Unit Maximum',
                                        'أقصى قيمة للوحدة'
                                    )
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
                                    $translate(
                                        'Unit Minimum',
                                        'أدنى قيمة للوحدة'
                                    )
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
                        </div>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="update">{{
                        $translate("Update Product", "تعديل منتج")
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import ProductService from "../../services/Product";
export default {
    name: "product.edit",
    data() {
        return {
            id: this.$route.params.id,
            categories: [],
            form: {
                ar_name: "",
                en_name: "",
                category_id: "",
                customizable: false,
                custom_price: null,
                unit_max: null,
                unit_min: null
            }
        };
    },
    beforeMount() {
        ProductService.GetCategories().then(response => {
            this.categories = response.data.categories;
        });

        ProductService.GetProduct(this.id).then(response => {
            this.form = response.data.product;
        });
    },
    methods: {
        update() {
            ProductService.UpdateProduct({ id: this.id, ...this.form }).then(
                () => {
                    this.$swal(
                        this.$translate(
                            "Operation done successfully !",
                            "تمت العملية بنجاح !"
                        ),
                        this.$translate(
                            "Product registered successfully",
                            "تمت إضافة المنتج بنجاح"
                        ),
                        "success"
                    ).then(() => {
                        this.$router.push("/products");
                    });
                }
            );
        }
    }
};
</script>

<style></style>
