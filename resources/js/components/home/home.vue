<template>
      <div style="text-align:center; padding-top: 12.5em;">
        <v-item-group>
      <v-container>
        <v-row>
          <v-col cols="12" md="4">
            <v-item>
              <v-card
                color="primary"
                class="d-flex align-center"
                dark
                height="250"
              >
                <v-card-text style="text-align: center; color: #fff;">
                  <v-icon x-large>mdi-account-supervisor</v-icon> <br />
                  <br />
                  <h3>إجمالي الزبائن</h3>
                  <br />
                  <br />
                  <h1>{{ usersCount }}</h1>
                </v-card-text>
              </v-card>
            </v-item>
          </v-col>
          <v-col cols="12" md="4">
            <v-item>
              <v-card
                color="green"
                class="d-flex align-center"
                dark
                height="250"
              >
                <v-card-text style="text-align: center; color: #fff;">
                  <v-icon x-large>mdi-newspaper</v-icon> <br />
                  <br />
                  <h3>إجمالي عدد الفواتير</h3>
                  <br />
                  <h1>{{ invoicesCount }}</h1>
                </v-card-text>
              </v-card>
            </v-item>
          </v-col>
          <v-col cols="12" md="4">
            <v-item>
              <v-card
                color="pink"
                class="d-flex align-center"
                dark
                height="250"
              >
                <v-card-text style="text-align: center; color: #fff;">
                  <v-icon x-large>mdi-cash-multiple</v-icon> <br />
                  <h3>إجمالي الفواتير</h3>
                  <br />
                  <h1>{{ invoicesTotal }}</h1>
                </v-card-text>
              </v-card>
            </v-item>
          </v-col>
        </v-row>
      </v-container>
    </v-item-group>
    </div>
</template>


<script>

export default {
name: 'home',
data() {
    return {
        usersCount: 3,
        invoicesCount: 2,
        invoicesTotal: 540
    };
},
beforeMount() {
  
},
methods: {
    
}
}
</script>

<style>

</style>