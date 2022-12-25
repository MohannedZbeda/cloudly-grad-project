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
                    { text: 'Cycle Months', value: 'months', sortable: false },
                    { text: 'State', value: 'state', sortable: false },
                    {
                        text: 'Addition Date',
                        value: 'created_at',
                        sortable: true
                    },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                [
                    {
                        text: 'الإسم',
                        align: 'start',
                        sortable: false,
                        value: 'name'
                    },
                    { text: 'أشهر الدفع', value: 'months', sortable: false },
                    { text: 'الحالة', value: 'state', sortable: false },
                    {
                        text: 'تاريخ الإضافة',
                        value: 'created_at',
                        sortable: true
                    },
                    { text: 'العمليات', value: 'actions', sortable: false }
                ]
            )
        "
        :items="cycles"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="$translate(`There's no cycles..`, 'لا يوجد دورات..')"
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
                                        cycle.enabled ? "disable " : "enable "
                                    }${cycle.name} `,
                                    `هل أنت متأكد من أنك تريد ${
                                        cycle.enabled ? "تعطيل " : "تفعيل "
                                    }${cycle.name}`
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

                
            </v-toolbar>
        </template>
        <template v-slot:[`item.state`]="{ item }">
            {{
                item.enabled
                    ? $translate("Active", "نشط")
                    : $translate("Inactvice", "غير نشط")
            }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
            <v-btn
                @click="prepareDialog(item)"
                :color="item.enabled ? '#c0392b' : '#2ecc71'"
                style="color:#fff"
            >
                {{
                    item.enabled
                        ? $translate("Disable Cycle", "تعطيل الدورة")
                        : $translate("Enable Cycle", "تفعيل الدورة")
                }}
            </v-btn>

        </template>
    </v-data-table>
</template>
<script>
import CycleService from "../../services/Cycle";

export default {
    name: "cycle.index",
    data() {
        return {
            activateDialog: false,
            
            cycles: [],
            cycle: {}
        };
    },

    beforeMount() {
        CycleService.GetCycles().then(response => {
            this.cycles = response.data.cycles;
        });
    },
    methods: {
        prepareDialog(item) {
            this.cycle = item;
            this.activateDialog = true;
        },

        closeDialog() {
            this.activateDialog = false;
            this.cycle = {};
        },
        changeState() {
            const payload = {
                cycle_id: this.cycle.id,
                status: !this.cycle.enabled
            };
            CycleService.ChangeState(payload)
                .then(response => {
                    this.activateDialog = false;
                    this.cycles = response.data.cycles;
                    this.cycle = {};
                })
                .catch(() => this.$unexpectedError());
        },

        
    }
};
</script>
