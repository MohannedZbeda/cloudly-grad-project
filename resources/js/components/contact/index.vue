<template>
<div>
     <v-btn style="margin: 3.2em 3.2em" v-if="emails.length" icon  @click="copyEmails">
      <v-icon>mdi-content-copy</v-icon>
    </v-btn> 

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
                    { text: 'Email', value: 'email', sortable: false },
                    { text: 'Message', value: 'message', sortable: false },
                    { text: 'Sent At', value: 'created_at', sortable: false }
                ],
                [
                    {
                        text: 'الإسم',
                        align: 'start',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'البريد الإلكتروني',
                        value: 'email',
                        sortable: false
                    }
                ]
            )
        "
        :items="emails"
        sort-by="created_at"
        class="elevation-1"
        calculated-width="true"
        :no-data-text="$translate(`There's no emails..`, 'لا يوجد إيميلات..')"
    >
    </v-data-table>
    </div>
</template>
<script>
import EmailService from "../../services/Email";

export default {
    name: "email.index",
    data() {
        return {
            emails: []
        };
    },

    beforeMount() {
        EmailService.GetEmails().then(response => {
            this.emails = response.data.emails;
        });
    },
    methods: {
        copyEmails() {
            let emails = "";
            this.emails.map(item => {
                return (emails += item["email"] + ", ");
            });
            emails = emails.slice(0, -2);
            const el = document.createElement("textarea");
            el.value = emails;
            el.setAttribute("readonly", "");
            el.style.position = "absolute";
            el.style.left = "-9999px";
            document.body.appendChild(el);
            el.select();
            document.execCommand("copy");
            document.body.removeChild(el);
        }
    }
};
</script>
