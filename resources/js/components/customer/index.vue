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
                    { text: 'Username', value: 'username', sortable: false },
                    { text: 'Phone Number', value: 'phone', sortable: false },
                    { text: 'Email', value: 'email', sortable: false },
                    {
                        text: 'Wallet Balance',
                        value: 'wallet_balance',
                        sortable: false
                    },
                    {
                        text: 'Addition Date',
                        value: 'created_at',
                        sortable: true
                    },
                    { text: 'Account State', value: 'state', sortable: false },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                [
                    {
                        text: 'الإسم',
                        align: 'start',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'إسم المستخدم',
                        value: 'username',
                        sortable: false
                    },
                    { text: 'رقم الهاتف', value: 'phone', sortable: false },
                    {
                        text: 'البريد الإلكتروني',
                        value: 'email',
                        sortable: false
                    },
                    {
                        text: 'رصيد المحفظة',
                        value: 'wallet_balance',
                        sortable: false
                    },
                    {
                        text: 'تاريخ الإضافة',
                        value: 'created_at',
                        sortable: true
                    },
                    { text: 'حالة الحساب', value: 'state', sortable: false },
                    { text: 'العمليات', value: 'actions', sortable: false }
                ]
            )
        "
        :items="customers"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="
            $translate(`There are no customers..`, 'لا يوجد زبائن..')
        "
    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-dialog v-model="activateDialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h6">
                            {{
                                $translate(
                                    `Are you sure you want to ${
                                        customer.state ? "disable " : "enable "
                                    }${customer.name} `,
                                    `هل أنت متأكد من أنك تريد ${
                                        customer.state ? "تعطيل " : "تفعيل "
                                    }${customer.name}`
                                )
                            }}
                        </v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="closeDialog"
                                >{{ $translate("Cancel", "إلغاء") }}</v-btn
                            >
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="changeState"
                                >{{ $translate("Yes", "أجل") }}</v-btn
                            >
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog v-model="walletDialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h6">
                            <v-text-field
                                :label="
                                    $translate('Charge Amount', 'قيمة الشحن')
                                "
                                :placeholder="
                                    $translate(
                                        'The Amount To Be Added To The Wallet',
                                        'القيمة المضافة للمحفظة'
                                    )
                                "
                                outlined
                                type="number"
                                v-model="amount"
                            ></v-text-field>
                        </v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="closeDialog"
                                >{{ $translate("Cancel", "إلغاء") }}</v-btn
                            >
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="walletConfirmDialog = true"
                                >{{ $translate("Charge", "شحن") }}</v-btn
                            >
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog v-model="walletConfirmDialog" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h6">
                            {{
                                $translate(
                                    `Are you sure you want to  charge ${customer.name}'s Wallet ? `,
                                    `هل أنت متأكد من أنك تريد شحن محفظة ${customer.name} ?`
                                )
                            }}
                        </v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="closeDialog"
                                >{{ $translate("Cancel", "إلغاء") }}</v-btn
                            >
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="chargeWallet"
                                >{{ $translate("Yes", "أجل") }}</v-btn
                            >
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
            <v-icon
                @click="prepareDialog(item)"
                :color="item.state ? '#c0392b' : '#2ecc71'"
            >
                {{ item.state ? "mdi-account-off" : "mdi-account-check" }}
            </v-icon>
            <v-icon
                style="margin-right : 10px"
                @click="prepareWalletDialog(item)"
            >
                mdi-wallet-plus
            </v-icon>
        </template>

        <template v-slot:[`item.state`]="{ item }">
            <v-icon :color="item.state ? '#2ecc71' : '#c0392b'"
                >mdi-checkbox-blank-circle</v-icon
            >
        </template>
    </v-data-table>
</template>
<script>
import CustomerService from "../../services/Customer";

export default {
    name: "customer.index",
    data() {
        return {
            activateDialog: false,
            walletDialog: false,
            walletConfirmDialog: false,
            customers: [],
            customer: {},
            amount: null
        };
    },

    beforeMount() {
        CustomerService.GetCustomers().then(response => {
            this.customers = response.data.customers;
        });
    },
    methods: {
        prepareDialog(item) {
            this.customer = item;
            this.activateDialog = true;
        },
        prepareWalletDialog(item) {
            this.customer = item;
            this.walletDialog = true;
        },
        closeDialog(walletConfirmOnly = false) {
            if (walletConfirmOnly) {
                this.walletConfirmDialog = false;
                return;
            }
            this.customer = {};
            this.activateDialog = false;
            this.walletDialog = false;
            this.walletConfirmDialog = false;
        },

        goToEdit(id) {
            this.$router.push("/customers/edit/" + id);
        },
        changeState() {
            const payload = {
                id: this.customer.id,
                state: !this.customer.state
            };
            CustomerService.ChangeState(payload)
                .then(response => {
                    this.activateDialog = false;
                    this.customers = response.data.customers;
                    this.customer = {};
                })
                .catch(() => this.$unexpectedError());
        },
        chargeWallet() {
            const payload = {
                wallet_id: this.customer.wallet.id,
                amount: this.amount
            };
            CustomerService.ChargeWallet(payload)
                .then(response => {
                    this.customers.forEach(customer => {
                      if(customer.id == this.customer.id)
                       customer.wallet_balance = response.data.wallet_balance;
                    });
                    this.closeDialog();
                })
                .catch(() => this.$unexpectedError());
        }
    }
};
</script>
