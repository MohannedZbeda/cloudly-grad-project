<template>
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
                        value: 'package_attributes'
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
                        value: 'package_attributes'
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
        :items="packages"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="$translate(`There's no packages..`, 'لا يوجد باقات..')"
    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{
                                $translate(
                                    `${pack.name}'s Attributes`,
                                    `خصائص  ${pack.name}`
                                )
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            <h3>
                                {{
                                    $translate(
                                        "Package Variants",
                                        "منتجات الباقة"
                                    )
                                }}
                            </h3>
                            <ul>
                                <li
                                    v-for="product in pack.products"
                                    :key="product.id"
                                >
                                    <b>{{
                                        product.name
                                    }}</b>
                                </li>
                            </ul>
                            <br />
                            <br />
                            <v-divider
                                style="background-color:black"
                            ></v-divider>
                            <br />
                            <br />
                            <h3>
                                {{
                                    $translate(
                                        "Package Payment Cycles",
                                        "دورات الدفع الخاصة بالباقة"
                                    )
                                }}
                            </h3>
                            <ul>
                                <li
                                    v-for="cycle in pack.cycles"
                                    :key="cycle.id"
                                >
                                    <b>{{
                                        cycle.name
                                    }}</b>
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

        <template v-slot:[`item.actions`]="{ item }">
            <v-icon style="margin-right : 10px" @click="goToEdit(item.id)"
                >mdi-pencil</v-icon
            >
        </template>

        <template v-slot:[`item.package_attributes`]="{ item }">
            <v-btn class="primary" @click="showAttributes(item)">{{
                $translate("View Attributes", "عرض الخصائص")
            }}</v-btn>
        </template>

    </v-data-table>
</template>
<script>
import PackageService from "../../services/Package";

export default {
    name: "package.index",
    data() {
        return {
            sending: false,
            dialog: false,
            packages: [],
            pack: {}
        };
    },

    beforeMount() {
        PackageService.GetPackages().then(response => {
            this.packages = response.data.packages;
        });
    },
    methods: {
        showAttributes(item) {
            this.pack = item;
            this.dialog = true;
        },
        
        // copyVouchers(vouchers) {
        //     const el = document.createElement("textarea");
        //     el.value = vouchers;
        //     el.setAttribute("readonly", "");
        //     el.style.position = "absolute";
        //     el.style.left = "-9999px";
        //     document.body.appendChild(el);
        //     el.select();
        //     document.execCommand("copy");
        //     document.body.removeChild(el);
        // }
        closeDialog() {
            this.dialog = false;
            this.pack = {};
        },
        goToEdit(id) {
            this.$router.push(`/packages/edit/${id}`);
        }
    }
};
</script>
