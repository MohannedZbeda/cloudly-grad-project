<template>
    <div>
        <v-btn style="margin : 1.7em" class="primary" @click="goToCreate">{{
            $translate("Add an Attribute", "إضافة خاصية")
        }}</v-btn>
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
                            text: 'Advanced ?',
                            sortable: false,
                            value: 'en_type'
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
                            text: 'خاصية أساسية ؟',
                            sortable: false,
                            value: 'ar_type'
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
            :items="attributes"
            sort-by="created_at"
            class="elevation-1"
            calculated-width="true"
            :no-data-text="
                $translate(`There's no attributes..`, 'لا يوجد خصائص..')
            "
        >
            <template v-slot:[`item.actions`]="{ item }">
                <v-icon style="margin-right : 10px" @click="goToEdit(item.id)"
                    >mdi-pencil</v-icon
                >
                <v-icon style="margin-right : 10px" @click="showValues(item)">
                    mdi-script-text</v-icon
                >
            </template>
        </v-data-table>
        <v-dialog v-model="createForm" max-width="500px">
            <v-card>
                <v-card-title class="text-h5"
                    >{{
                        $translate(
                            `Add Value to ${attribute.en_name}'`,
                            `إضافة قيمة ل  ${attribute.ar_name}`
                        )
                    }}<br />
                </v-card-title>
                <v-card-text dir="rtl">
                    <v-form>
                        <v-text-field
                            :label="$translate('Value', 'القيمة')"
                            :placeholder="
                                $translate('Attribute Value', 'قيمة الخاصية')
                            "
                            outlined
                            v-model="valueForm.value"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDialog">{{
                        $translate("Cancel", "إلغاء")
                    }}</v-btn>
                    <v-btn color="primary" text @click="addValue">{{
                        $translate("Add", "إضافة")
                    }}</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="valuesDialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5"
                    >{{
                        $translate(
                            `${attribute.en_name}'s Values`,
                            `قيم ${attribute.ar_name}`
                        )
                    }}<br />
                </v-card-title>
                <v-card-text dir="rtl">
                    <h3>
                        {{ $translate("Values", "القيم") }}
                    </h3>
                    <ul>
                        <li v-for="value in attribute.values" :key="value.id">
                            <b>{{ value.value }}</b>
                        </li>
                    </ul>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDialog">{{
                        $translate("Cancel", "إلغاء")
                    }}</v-btn>
                    <v-btn color="primary" text @click="showCreateForm">{{
                        $translate("Add a Value", "إضافة قيمة")
                    }}</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import CategoryService from "../../../services/Category";
import Service from "../../../services/Value";
var ValueService;

export default {
    name: "attribute.index",
    data() {
        return {
            id: this.$route.params.category_id,
            activateDialog: false,
            valuesDialog: false,
            createForm: false,
            attributes: [],
            attribute: {},
            valueForm: {
                value: null
            },
            form: {
                ar_name: "",
                en_name: ""
            }
        };
    },

    beforeMount() {
        CategoryService.GetAttributes(this.id).then(response => {
            this.attributes = response.data.attributes;
        });
    },
    methods: {
        goToCreate() {
            this.$router.push("/categories/" + this.id + "/attributes/create");
        },
        goToEdit(id) {
            this.$router.push(
                "/categories/" + this.id + "/attributes/edit/" + id
            );
        },
        showValues(attribute) {
            this.valuesDialog = true;
            this.attribute = attribute;
        },
        showCreateForm() {
            this.createForm = true;
        },
        addValue() {
            ValueService = new Service(this.attribute.id);
            ValueService.CreateValue({
                attribute_id: this.attribute.id,
                ...this.valueForm
            }).then(response => {
              let index = this.attributes.findIndex((attribute => attribute.id == this.attribute.id));
              this.attributes[index].values.push(response.data.value); 
              this.valueForm.value = null;
              this.createForm = false;
            });
        },
        closeDialog() {
            this.attribute = {};
            this.createForm = false;
            this.valuesDialog = false;
        },
        addAttribute() {
            const payload = {
                category_id: this.id,
                ...this.form
            };
            CategoryService.AddAttribute(payload)
                .then(response => {
                    this.$swal(
                        this.$translate(
                            "Operation done successfully !",
                            "تمت العملية بنجاح !"
                        ),
                        this.$translate(
                            "Attribute registered successfully",
                            "تمت إضافة الخاصية بنجاح"
                        ),
                        "success"
                    ).then(() => {
                        this.categories.map(cat => {
                            if (cat.id == this.category.id)
                                cat.attributes.push(response.data.attribute);
                        });
                        this.additionMode = false;
                        this.closeDialog();
                    });
                })
                .catch(() => this.$unexpectedError());
        }
    }
};
</script>
