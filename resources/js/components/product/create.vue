<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-card style="min-width : 50%" class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{
                        $translate("Register a Product", "إضافة منتج")
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <p v-if="noCycles" style="color:red">
                        {{
                            $translate(
                                "There are no payment cycles, Please add some before creating a product",
                                "لا توجد دورات دفع مضافة, يرجى الإضافة"
                            )
                        }}
                    </p>
                    <v-form>
                        <v-select
                            :items="categories"
                            item-text="name"
                            item-value="id"
                            :label="$translate('Product Category', 'تصنيف المنتج')"
                            v-model="form.category_id"
                            outlined
                            :error-messages="errors.category_id ? $translate(errors.category_id[0].en, errors.category_id[0].ar) : null"
                        ></v-select>
                        <v-text-field
                            :label="$translate('Name', 'الإسم')"
                            :placeholder="
                                $translate(
                                    'Product Name',
                                    'إسم المنتج'
                                )
                            "
                            outlined
                            v-model="form.name"
                            :error-messages="errors.name ? $translate(errors.name[0].en, errors.name[0].ar) : null"
                        ></v-text-field>
                        <v-textarea
                            :label="$translate('Description', 'الوصف')"
                            :placeholder="$translate('Product NDescriptioname', 'وصف المنتج')"
                            outlined
                            v-model="form.description"
                            :error-messages="errors.description ? $translate(errors.description[0].en, errors.description[0].ar) : null"
                        ></v-textarea>
                        <v-text-field
                            :label="$translate('Price', 'السعر')"
                            :placeholder="
                                $translate(
                                    'Product monthly Price',
                                    'السعر الشهري للمنتج'
                                )
                            "
                            outlined
                            min="0"
                            v-model="form.price"
                            :error-messages="errors.price ? $translate(errors.price[0].en, errors.price[0].ar) : null"
                        ></v-text-field>
                        <v-autocomplete
                            v-model="form.cycles"
                            :items="cycles"
                            item-text="name"
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
                            :error-messages="errors.cycles ? $translate(errors.cycles[0].en, errors.cycles[0].ar) : null"
                        >
                        </v-autocomplete>
                        <v-file-input
                            accept="image/png,
                             image/jpeg, image/jpg"
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
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        :disabled="allowSubmit"
                        color="primary"
                        @click="create"
                        >{{
                            $translate("Register Product", "إضافة منتج")
                        }}</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import ProductService from "../../services/Product";
import CycleService from "../../services/Cycle";
export default {
    name: "product.create",
    data() {
        return {
            categories: [],
            cycles: [],
            image: null,
            noCycles: false,
            form: {
                name: "",
                description: "",
                price: null,
                category_id: "",
                base64image: null,
                cycles: []
            },
            errors: []
        };
    },
    beforeMount() {
        ProductService.GetCategories().then(response => {
            this.categories = response.data.categories;
        });
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
        create() {

            ProductService.CreateProduct(this.form).then(() => {
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
            }).catch(errors => {
                this.errors = errors.response.data.errors;
            });
        }
    },
    watch: {
        image: {
            handler() {
                this.toBase64();
            }
        }
    },
    computed: {
        allowSubmit() {
            return this.form.base64image == null;
        }
    }
};
</script>

