<template>
  <v-data-table
    :headers="$translate([
         {
          text: 'Start Date',
          align: 'start',
          sortable: true,
          value: 'start_date',
        },
        {
          text: 'End Date',
          sortable: true,
          value: 'end_date',
        },
        {
          text: 'Amount',
          sortable: true,
          value: 'amount',
        },
        {
          text: 'Used Amount',
          sortable: true,
          value: 'used_amount',
        },
        {
          text: 'Discount Percentage',
          sortable: false,
          value: 'discount_percentage',
        },
        { text: 'Actions', value: 'actions', sortable: false },
      ],[
         {
          text: 'تاريخ بدء الصلاحية',
          align: 'start',
          sortable: false,
          value: 'start_date',
        },
        {
          text: 'تاريخ إنتهاء الصلاحية',
          align: 'start',
          sortable: false,
          value: 'end_date',
        },
        {
          text: 'العدد',
          align: 'start',
          sortable: false,
          value: 'amount',
        },
        {
          text: 'العدد المستعمل',
          sortable: true,
          value: 'used_amount',
        },
        {
          text: 'نسبة التخفيض',
          sortable: false,
          value: 'discount_percentage',
        },
        { text: 'العمليات', value: 'actions', sortable: false }
      ])"
    :items="coupons"
    sort-by="created_at"
    class="elevation-1"
    calculated-width="true"
    :no-data-text="$translate(`There's no coupons..`, 'لا يوجد كوبونات..')"
  >
  
    <template v-slot:[`item.actions`]="{ item }">
      <v-icon style="margin-right : 10px" @click="goToEdit(item)">mdi-pencil</v-icon>
      <v-icon style="margin-right : 10px" @click="copyCodes(item.codes)">mdi-content-copy</v-icon>
    </template>    
    
  </v-data-table>
</template>
<script>    

import CouponService from '../../services/Coupon';

  export default {
    name : 'coupon.index',
    data() {
      return {
      dialog : false,
      coupons: [],
      coupon: {}
      } 
    },

    beforeMount(coupon) {
      CouponService.GetCoupons().then(response => {
        this.coupons = response.data.coupons
      });
    },
    methods: {
    goToEdit(coupon) {
     this.$router.push(`/coupons/edit/${coupon.start_date}/${coupon.end_date}`);
    },
    copyCodes(codes) {
      const el = document.createElement('textarea');
      el.value = codes;
      el.setAttribute('readonly', '');
      el.style.position = 'absolute';
      el.style.left = '-9999px';
      document.body.appendChild(el);
      el.select();
      document.execCommand('copy');
      document.body.removeChild(el);
    } 

  }  
}
</script>