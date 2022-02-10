<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-card style="min-width : 50%" class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{
                        $translate("Register a Variant", "إضافة تنوع من منتج")
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <p v-if="noAttributes" style="color:red">
                        {{
                            $translate(
                                "There are no attributes for this product, Please add some",
                                "لا توجد خصائص لهذا المنتج, يرجى الإضافة"
                            )
                        }}
                    </p>

                   
                    <v-form>
                        <v-text-field
                            :label="$translate('AR Name', 'الإسم بالعربي')"
                            :placeholder="
                                $translate(
                                    'AR Variant Name',
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
                                    'EN Variant Name',
                                    'إسم المنتج بالإنجليزي'
                                )
                            "
                            outlined
                            v-model="form.en_name"
                        ></v-text-field>

                        
                        <v-divider style="background-color: black"></v-divider>
                        <br />
                        <br />
                        <v-row
                                v-for="attribute in form.attributes"
                                :key="attribute.id"
                            >
                                <v-col cols="2" sm="2" md="1">
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
                                        
                                <v-col cols="2" sm="2" md="3">
                                    <v-select
                                        :label="
                                            $translate(
                                                'Value',
                                                'القيمة'
                                            )
                                        "
                                        outlined
                                        :items="attribute.values"
                                        item-text="value"
                                        item-value="id"
                                        v-model="attribute.value_id"
                                    ></v-select>
                                </v-col>
                                <br />
                                <br />
                            </v-row>
                        <br />
                        <br />
                        <v-divider style="background-color: black"></v-divider>
                        <br />
                        <br />
                        <v-text-field
                            :label="$translate('Price', 'السعر')"
                            outlined
                            type="number"
                            v-model="form.price"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="create">{{
                        $translate("Register Variant", "إضافة")
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import VariantService from "../../services/Variant";
export default {
    name: "variant.create",
    data() {
        return {
            id: this.$route.params.id,
            noAttributes: false,
            form: {
                ar_name: "",
                en_name: "",
                attributes: [],
                price: null
            }
        };
    },
    beforeMount() {
        this.getAttributes(this.id);
    },
    methods: {
        getAttributes(id) {
            VariantService.GetAttributes({ id }).then(response => {
                if (!response.data.attributes.length) {
                    this.noAttributes = true;
                    this.form.attributes = [];
                    return;
                }
                this.noAttributes = false;
                this.form.attributes = response.data.attributes.map(
                    attribute => {
                        return {
                            id: attribute.id,
                            ar_name: attribute.ar_name,
                            en_name: attribute.en_name,
                            advanced: attribute.advanced,
                            values: attribute.values
                        };
                    }
                );
            });
        },
        removeAttribute(id) {
            if (this.form.attributes.length <= 1) return;
            this.form.attributes = this.form.attributes.filter(attribute => {
                return attribute.id != id;
            });
        },
        create() {
            const payload = {
                product_id: this.id,
                ...this.form
            };
            VariantService.CreateVariant(payload).then(() => {
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
                    this.$router.push(`/products/${this.id}/variants`);
                });
            });
        }
    }
};
</script>
<style>
.v-list {
    display: flex !important;
    flex-direction: column;
}
</style>
