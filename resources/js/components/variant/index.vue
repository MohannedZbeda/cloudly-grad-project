<template>
    <div>
        <h2 style="text-align:center; margin-top: 2em">
            {{ $translate("Product Variants", "تفرعات المنتج") }}
        </h2>
        <br />
        <v-btn style="margin-start: 4em" class="primary" @click="goToCreate">{{
            $translate("Add a Variant", "إضافة تفرع")
        }}</v-btn>
        <br />
        <br />
        <v-data-table
            :headers="
                $translate(
                    [
                        {
                            text: 'Name',
                            align: 'start',
                            sortable: false,
                            value: 'name'
                        },
                        {
                            text: 'Attributes',
                            sortable: false,
                            value: 'attributes'
                        },
                        {
                            text: 'Product',
                            sortable: false,
                            value: 'product'
                        },
                        {
                            text: 'Addition Date',
                            value: 'created_at',
                            sortable: true
                        },
                        { text: 'Actions', value: 'actions', sortable: false }
                    ],
                    [
                        {
                            text: 'الإسم ',
                            align: 'start',
                            sortable: false,
                            value: 'name'
                        },
                        {
                            text: 'الخصائص',
                            sortable: false,
                            value: 'attributes'
                        },
                        {
                            text: 'المنتج',
                            sortable: false,
                            value: 'product'
                        },
                        {
                            text: 'تاريخ الإضافة',
                            value: 'created_at',
                            sortable: true
                        },
                        { text: 'العمليات', value: 'actions', sortable: false }
                    ]
                )
            "
            :items="variants"
            sort-by="created_at"
            calculated-width="true"
            :no-data-text="
                $translate(`There's no variants..`, 'لا يوجد تفرعات..')
            "
        >
            <template v-slot:[`item.actions`]="{ item }">
                <v-icon style="margin-right: 10px" @click="goToEdit(item.id)"
                    >mdi-pencil</v-icon
                >
            </template>

            <template v-slot:[`item.attributes`]="{ item }">
                <v-btn class="primary" @click="showAttributes(item)">{{
                    $translate("View Attributes", "عرض الخصائص")
                }}</v-btn>
            </template>

            
        </v-data-table>
        <br />
        <br />
        <br />
        <div v-if="cycles.length">
            <v-divider style="background-color: black"></v-divider>
            <h2 style="text-align:center; margin-top: 2em">
                {{
                    $translate(
                        "Product payment cycles",
                        "دورات الدفع المتاحة للمنتج"
                    )
                }}
            </h2>
            <br />
            <br />
            <br />
            <v-data-table
                :headers="
                    $translate(
                        [
                            {
                                text: 'AR Name',
                                align: 'start',
                                sortable: false,
                                value: 'ar_name'
                            },
                            {
                                text: 'EN Name',
                                sortable: false,
                                value: 'en_name'
                            },
                            {
                                text: 'Cycle Months',
                                sortable: false,
                                value: 'months'
                            },
                            {
                                text: 'State',
                                value: 'state',
                                sortable: false
                            }
                        ],
                        [
                            {
                                text: 'الإسم ',
                                align: 'start',
                                sortable: false,
                                value: 'name'
                            },
                            {
                                text: 'عدد الأشهر',
                                sortable: false,
                                value: 'months'
                            },
                            {
                                text: 'الحالة',
                                value: 'state',
                                sortable: false
                            }
                        ]
                    )
                "
                :items="cycles"
                calculated-width="true"
                :no-data-text="
                    $translate(`There are no cycles..`, 'لا يوجد دورات دفع, يرجى الإضافة..')
                "
            >
                <template v-slot:[`item.state`]="{ item }">
                    <v-icon :color="item.enabled ? '#2ecc71' : '#c0392b'"
                        >mdi-checkbox-blank-circle</v-icon
                    >
                </template>
            </v-data-table>
        </div>
        <template>
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{
                                $translate(
                                    `${variant.name}'s Attributes`,
                                    `خصائص ${variant.name}`
                                )
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            <ul>
                                <li
                                    v-for="attribute in variant.attributes"
                                    :key="attribute.id"
                                >
                                    <b>{{
                                            attribute.name,
                                    }}</b>
                                    : {{ attribute.value }}
                                </li>
                            </ul>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="closeDialog"
                                >{{ $translate("Cancel", "إلغاء") }}</v-btn
                            >
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            
            
            
        </template>
    </div>
</template>
<script>
import Variantservice from "../../services/Variant";
export default {
    name: "variant.index",
    data() {
        return {
            id: this.$route.params.id,
            sending: false,
            dialog: false,
            variants: [],
            cycles: [],
            variant: {}
        };
    },

    beforeMount() {
        Variantservice.GetVariants(this.id).then(response => {
            this.variants = response.data.variants;
            this.cycles = response.data.cycles;
        });
    },
    methods: {
        goToCreate() {
            return this.$router.push(`/products/${this.id}/variants/create`);
        },
        showAttributes(item) {
            this.variant = item;
            this.dialog = true;
        },
        closeDialog() {
            this.dialog = false;
            this.variant = {};
        },
        goToEdit(id) {
            this.$router.push(`/products/${this.id}/variants/edit/${id}`);
        },
        goToAttributeEdit(id) {
            this.$router.push(`/products/${this.id}/custom-attributes/${id}`);
        }
    }
};
</script>
