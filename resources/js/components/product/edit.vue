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
                    <p
                        v-if="noAttributes && form.customizable"
                        style="color:red"
                    >
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
                        <v-autocomplete
                            v-model="form.cycles"
                            :items="cycles"
                            :item-text="$translate('en_name', 'ar_name')"
                            item-value="id"
                            outlined
                            dense
                            chips
                            small-chips
                            :label="
                                $translate(
                                    'Available Payment Cycles',
                                    'دورات الدفع المتاحة'
                                )
                            "
                            multiple
                        >
                        </v-autocomplete>
                        <v-file-input
                            accept="image/png, image/jpeg, image/jpg"
                            :placeholder="
                                $translate('Product Image', 'صورة المنتج')
                            "
                            prepend-icon="mdi-image-filter-hdr"
                            :label="$translate('Product Image', 'صورة المنتج')"
                            v-model="image"
                            outlined
                        ></v-file-input>
                        <br />
                        <v-img
                            style="margin: 0 10px"
                            v-if="form.base64image"
                            max-height="300"
                            max-width="300"
                            :src="form.base64image"
                        ></v-img>
                        <br />
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

                        <v-container v-if="form.customizable">
                            <v-row
                                v-for="attribute in form.custom_attributes"
                                :key="attribute.attribute_id"
                            >
                                <v-col cols="6" sm="3" md="1">
                                    <h3 style="margin-top: 1em">
                                        {{
                                            $translate(
                                                attribute.en_name,
                                                attribute.ar_name
                                            )
                                        }}
                                        :
                                    </h3>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
                                    <v-text-field
                                        :label="
                                            $translate(
                                                'Custom Price',
                                                'سعر الوحدة'
                                            )
                                        "
                                        :placeholder="
                                            $translate(
                                                'Custom price per unit',
                                                'سعر الوحدة'
                                            )
                                        "
                                        outlined
                                        type="number"
                                        v-model="attribute.custom_price"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="6" md="3">
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
                                        v-model="attribute.unit_max"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="6" md="3">
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
                                        v-model="attribute.unit_min"
                                    ></v-text-field>
                                </v-col>
                                <br />
                                <br />
                            </v-row>
                        </v-container>
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
import CycleService from "../../services/Cycle";
export default {
    name: "product.edit",
    data() {
        return {
            id: this.$route.params.id,
            categories: [],
            cycles: [],
            image: null,
            noAttributes: false,
            form: {
                ar_name: "",
                en_name: "",
                category_id: "",
                customizable: false,
                custom_attributes: [],
                cycles: [],
                base64image: null
            }
        };
    },
    beforeMount() {
        ProductService.GetCategories().then(response => {
            this.categories = response.data.categories;
        });

        ProductService.GetProduct(this.id).then(response => {
            this.form = response.data.product;
            this.getAttributes();
        });
    },
    methods: {
        toBase64() {
            if (!this.image) {
                this.form.base64image = null;
                return;
            }
            const reader = new FileReader();
            reader.readAsDataURL(this.image);
            reader.onload = () => {
                this.form.base64image = reader.result;
            };
        },
        getAttributes() {
            ProductService.GetAttributes(this.form.category_id).then(
                response => {
                    if (!response.data.attributes.length) {
                        this.noAttributes = true;
                        this.form.custom_attributes = [];
                        return;
                    }
                    this.noAttributes = false;
                    this.form.custom_attributes = response.data.attributes.map(
                        attribute => {
                            return {
                                attribute_id: attribute.id,
                                ar_name: attribute.ar_name,
                                en_name: attribute.en_name,
                                custom_price: null,
                                unit_max: null,
                                unit_min: null
                            };
                        }
                    );
                }
            );
            CycleService.AllCycles().then(response => {
                if (!response.data.cycles.length) {
                    this.noCycles = true;
                    this.form.cycles = [];
                    return;
                }
                this.noCycles = false;
                this.cycles = response.data.cycles;
            });
        },
        update() {
            this.form.cycles = this.form.cycles.map(cycle =>
                cycle["id"] ? cycle["id"] : cycle
            );
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
    },
     watch: {
        image: {
            handler() {
                this.toBase64();
            }
        }
    }
};
</script>

<style></style>
