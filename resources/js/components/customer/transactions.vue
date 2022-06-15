<template>
    <v-data-table
        :headers="
            $translate(
                [
                    {
                        text: 'Description',
                        align: 'start',
                        sortable: false,
                        value: 'description'
                    },
                    { text: 'Type', value: 'type.en', sortable: false },
                    { text: 'Amount', value: 'amount', sortable: true },
                    { text: 'Date Of Occurrence', value: 'created_at', sortable: false }
                ],
                [
                    {
                        text: 'الوصف',
                        align: 'start',
                        sortable: false,
                        value: 'description'
                    },
                    {
                        text: 'النوع',
                        value: 'type.ar',
                        sortable: false
                    },
                    { text: 'القيمة', value: 'amount', sortable: true },
                    {
                        text: 'تاريخ الوقوع',
                        value: 'created_at',
                        sortable: true
                    }
                ]
            )
        "
        :items="transactions"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="
            $translate(`There are no transactions..`, 'لا يوجد معاملات..')
        "
    >
        
    </v-data-table>
</template>
<script>
import CustomerService from "../../services/Customer";

export default {
    name: "customer.transactions",
    data() {
        return {
            transactions: [],
        };
    },

    beforeMount() {
        CustomerService.GetTransactions(this.$route.params.id).then(response => {
            this.transactions = response.data.transactions;
        });
    }
};
</script>
