<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-card style="min-width : 50%" class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{
                        $translate("Register a Cycle", "إضافة دورة دفع")
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            :label="$translate('Name', ' الإسم')"
                            :placeholder="
                                $translate(
                                    'AR Name',
                                    'إسم دورة الدفع'
                                )
                            "
                            outlined
                            v-model="form.name"
                            :error-messages="errors.name ? $translate(errors.name[0].en, errors.name[0].ar) : null"

                        ></v-text-field>
                        <v-text-field
                            :label="
                                $translate(
                                    'Cycle Months',
                                    'أشهر الخصم في الدورة'
                                )
                            "
                            :placeholder="
                                $translate(
                                    'Months to pass before retrieving payment',
                                    'عدد الأشهر حتى يتم الخصم'
                                )
                            "
                            outlined
                            type="number"
                            min="1"
                            v-model="form.months"
                            :error-messages="errors.months ? $translate(errors.months[0].en, errors.months[0].ar) : null"
                        ></v-text-field>
                        
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="create">{{
                        $translate("Register", "إضافة")
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-layout>
    </v-container>
</template>

<script>
import CycleService from "../../services/Cycle";
export default {
    name: "cycle.create",
    data() {
        return {
            form: {
                name: "",
                months: null
            },
            errors: []
        };
    },

    methods: {
        async create() {
            CycleService.CreateCycle(this.form).then(() => {
                this.$swal(
                    this.$translate(
                        "Operation done successfully !",
                        "تمت العملية بنجاح !"
                    ),
                    this.$translate(
                        "Cycle registered successfully",
                        "تمت إضافة دورة الدفع بنجاح"
                    ),
                    "success"
                ).then(() => {
                    this.$router.push("/cycles");
                });
            }).catch(errors => {
                this.errors = errors.response.data.errors;
            });
        }
    }
};
</script>

<style></style>
