<template>
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
                        text: 'Attributes',
                        sortable: false,
                        value: 'package_attributes'
                    },
                    {
                        text: 'Discount',
                        sortable: false,
                        value: 'discount'
                    },
                    {
                        text: 'Price Before Discount',
                        sortable: false,
                        value: 'price'
                    },
                    {
                        text: 'Price After Discount',
                        sortable: false,
                        value: 'new_price'
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
                        text: 'الإسم بالعربي',
                        align: 'start',
                        sortable: false,
                        value: 'ar_name'
                    },
                    {
                        text: 'الإسم بالإنجليزي',
                        sortable: false,
                        value: 'en_name'
                    },
                    {
                        text: 'الخصائص',
                        sortable: false,
                        value: 'package_attributes'
                    },
                    {
                        text: 'التخفيض',
                        sortable: false,
                        value: 'discount'
                    },
                    {
                        text: 'السعر قبل التخفيض',
                        sortable: false,
                        value: 'price'
                    },
                    {
                        text: 'السعر بعد التخفيض',
                        sortable: false,
                        value: 'new_price'
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
                                    `${pack.en_name}'s Attributes`,
                                    `خصائص  ${pack.ar_name}`
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
                                    v-for="variant in pack.variants"
                                    :key="variant.id"
                                >
                                    <b>{{
                                        $translate(
                                            variant.en_name,
                                            variant.ar_name
                                        )
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
                                        $translate(cycle.en_name, cycle.ar_name)
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
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="add_vouchers_dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{
                                $translate(
                                    `Add Vouchers to ${pack.en_name}`,
                                    `إضافة هدايا ل ${pack.ar_name}`
                                )
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            <b>{{
                                $translate(
                                    "Gifs are codes to be given to chosen users, When used, The codes give the chosen product",
                                    "الهدايا هي عبارة عن رموز تعطى لمستخدمين مختارين, عندما يتم إستعمال هذه الأكواد, ستعطي المنتج المختار مجانا"
                                )
                            }}</b
                            ><br /><br />
                            <v-form>
                                <v-text-field
                                    :label="$translate('Quantity', 'الكمية')"
                                    outlined
                                    type="number"
                                    v-model="form.quantity"
                                ></v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="addVouchers">{{
                                $translate("Add Vouchers", "إضافة الهدايا")
                            }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="delete_discount_dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{ $translate("Delete Discount", "حذف تخفيض")
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            {{
                                $translate(
                                    `Are you sure you want to delete ${pack.en_name}'s Discount ?`,
                                    `  هل أنت متأكد من أنك تريد حذف تخفيض المنتج ${pack.ar_name} `
                                )
                            }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="removeDiscount"
                                >{{ $translate("Yes", "نعم") }}</v-btn
                            >
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

            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="add_discount_dialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5"
                            >{{
                                $translate(
                                    `Add Discount to ${pack.en_name}`,
                                    `إضافة تخفيض ل ${pack.ar_name}`
                                )
                            }}<br />
                        </v-card-title>
                        <v-card-text dir="rtl">
                            <v-form>
                                <v-text-field
                                    :label="
                                        $translate(
                                            'Discount Percentage',
                                            'نسبة التخفيض'
                                        )
                                    "
                                    outlined
                                    type="number"
                                    v-model="form.discount_percentage"
                                ></v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="addDiscount">{{
                                $translate("Add Discount", "إضافة التخفيض")
                            }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>

        <template v-slot:[`item.actions`]="{ item }">
            <v-icon style="margin-right : 10px" @click="goToEdit(item.id)"
                >mdi-pencil</v-icon
            >
            <v-icon style="margin-right: 10px" @click="showVoucherForm(item)"
                >mdi-gift</v-icon
            >
            <v-icon
                v-if="item.vouchers.length"
                style="margin-right: 10px"
                @click="copyVouchers(item.vouchers)"
                >mdi-content-copy</v-icon
            >
        </template>

        <template v-slot:[`item.package_attributes`]="{ item }">
            <v-btn class="primary" @click="showAttributes(item)">{{
                $translate("View Attributes", "عرض الخصائص")
            }}</v-btn>
        </template>

        <template v-slot:[`item.discount`]="{ item }">
            <div v-if="item.discount">
                <b>{{ item.discount }} | </b>
                <v-icon
                    style="margin-right: 10px; color: #c0392b"
                    @click="showDiscountDeleteForm(item)"
                    >mdi-delete</v-icon
                >
            </div>
            <div v-else>
                <b>{{ $translate("There is none", "لايوجد") }} | </b>
                <v-icon
                    style="color: #2ecc71"
                    @click="showDiscountForm(item, false)"
                    >mdi-plus</v-icon
                >
            </div>
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
            delete_discount_dialog: false,
            add_vouchers_dialog: false,
            add_discount_dialog: false,
            packages: [],
            pack: {},
            form: {
                quantity: null,
                discount_percentage: null
            }
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
        showDiscountForm(item) {
            this.pack = item;
            this.add_discount_dialog = true;
        },
        showVoucherForm(item) {
            this.pack = item;
            this.add_vouchers_dialog = true;
        },
        showDiscountDeleteForm(item) {
            this.pack = item;
            this.delete_discount_dialog = true;
        },
        copyVouchers(vouchers) {
            const el = document.createElement("textarea");
            el.value = vouchers;
            el.setAttribute("readonly", "");
            el.style.position = "absolute";
            el.style.left = "-9999px";
            document.body.appendChild(el);
            el.select();
            document.execCommand("copy");
            document.body.removeChild(el);
        },
        addVouchers() {
            if (this.sending) return;
            this.sending = true;
            const payload = {
                quantity: this.form.quantity,
                package_id: this.pack.id
            };
            PackageService.AddVouchers(payload)
                .then(response => {
                    this.packages = response.data.packages;
                    this.$swal(
                        this.$translate(
                            "Operation done successfully !",
                            "تمت العملية بنجاح !"
                        ),
                        this.$translate(
                            "Vouchers generated successfully",
                            "تمت توليد الهدايا بنجاح"
                        ),
                        "success"
                    );
                })
                .finally(() => {
                    this.sending = false;
                    this.closeDialog();
                });
        },
        removeDiscount(id) {
            if (this.sending) return;
            const payload = {
                package_id: this.pack.id,
                discount_id: id
            };
            this.sending = true;
            PackageService.RemoveDiscount(payload)
                .then(response => {
                    this.packages = response.data.packages;
                })
                .finally(() => {
                    this.sending = false;
                    this.closeDialog();
                });
        },
        addDiscount() {
            if (this.sending) return;
            const payload = {
                package_id: this.pack.id,
                discount_percentage: this.form.discount_percentage
            };
            this.sending = true;
            PackageService.AddDiscount(payload)
                .then(response => {
                    this.packages = response.data.packages;
                    this.$swal(
                        this.$translate(
                            "Operation done successfully !",
                            "تمت العملية بنجاح !"
                        ),
                        this.$translate(
                            "Discount Added successfully",
                            "تم إضافة التخفيض بنجاح"
                        ),
                        "success"
                    );
                })
                .finally(() => {
                    this.sending = false;
                    this.closeDialog();
                });
        },
        closeDialog() {
            this.dialog = this.discounts_dialog = this.add_vouchers_dialog = this.add_discount_dialog = this.delete_discount_dialog = false;
            this.pack = {};
        },
        goToEdit(id) {
            this.$router.push(`/packages/edit/${id}`);
        }
    }
};
</script>
