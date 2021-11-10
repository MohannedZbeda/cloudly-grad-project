<template>
  <v-app  id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      :right="getDir()"
    >
      
      <v-list-item>
        <v-list-item-content style="display: flex; align-items: center; padding : 1.8em">
           <v-avatar size="2.2em" >
              <img alt="User Avatar" src="/images/man.png">
           </v-avatar>
            <h3 style="text-align : center; padding : .6em 0"> <b>{{user.name}}</b></h3> 
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
      
      
      
 <v-list>
      

 <v-list-group  no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="">mdi-account-supervisor</v-icon> {{$translate('Admins', 'المشرفين')}}</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/admins" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-account-details</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('All Admins', 'جميع المشرفين')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>


        <router-link to="/admins/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-account-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('Add Admin', 'إضافة مشرف')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </router-link>  
      </v-list-group>

      <v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-solar-panel</v-icon>{{$translate('Categories', 'التصنيفات')}}</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/categories" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-format-list-bulleted</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('All Categories', 'جميع التصنيفات')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>


        <router-link to="/categories/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('Add a Category', 'إضافة تصنيف')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </router-link>  
      </v-list-group>
     
     
      <v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-archive</v-icon>{{$translate('Products', 'المنتجات')}}</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/products" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-format-list-bulleted</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('All Products', 'جميع المنتجات')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>


        <router-link to="/products/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('Add a Product', 'إضافة منتج')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </router-link>  
      </v-list-group>

      <v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-handshake</v-icon>  العملاء - Clients</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/clients" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-format-list-bulleted</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title> جميع العملاء - All Clients</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>


        <router-link to="/clients/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>إضافة عميل - Add Client</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </router-link>  
      </v-list-group>

      <v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-account-multiple</v-icon>  الشركاء - Partners</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/partners" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-format-list-bulleted</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title> عرض الشركاء - All Partners</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>


        <router-link to="/partners/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>إضافة شريك - Add Partner</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </router-link>  
      </v-list-group>
    
  

      <v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-message</v-icon>  الرسائل - Messages</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/messages/sent" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-message-text</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title> المرسلة - Sent</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>

          <router-link to="/messages/recieved" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-inbox-arrow-down</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>  المستلمة - Recieved</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>  
      </v-list-group>

      <v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-briefcase</v-icon>  الوظائف - Jobs</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/jobs" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-briefcase-eye</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>  عرض كل الوظائف - All Jobs</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>

          <router-link to="/jobs/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-briefcase-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>  إضافة وظيفة - Add Job</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>  
      </v-list-group>

<v-list-group no-action>
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title><v-icon style="margin : 10dp">mdi-download</v-icon>  التنزيلات - downloadable Content</v-list-item-title>
          </v-list-item-content>
        </template>

        
        <router-link to="/downloads" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-download-multiple</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title> عرض التنزيلات - Show Downloadable Content</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>


        <router-link to="/downloads/create" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>إضافة تنزيل - Add Downloadable Content</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        </router-link>  
      </v-list-group>
    </v-list>
      <v-list dense>
    
        
        
      

        <router-link to="/emails" class="link">
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-email</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>  اﻹيميلات - Emails</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
          </router-link>

          <v-list-item link @click="drawer = false; $store.commit('logout')">
          <v-list-item-action>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{$translate('Logout', 'تسجيل الخروج')}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar app color="primary" dark
    >
      <v-app-bar-nav-icon   @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <h4 style="padding : 0 1.2em">{{$translate('Datacenter - Dashboard', 'مركز البيانات - لوحة التحكم')}}</h4>
     <v-spacer></v-spacer>
     
    <v-btn
      class="mx-2"
      small
      outlined
      color="white"
      @click="changeLang()"
    >
      <b style="padding : 0 .5em">{{$translate('العربية', 'English')}}</b>      
      <v-icon small>
        mdi-translate
      </v-icon>
    </v-btn>
       
    </v-app-bar>

    <v-main>
      
      <router-view></router-view>
    </v-main>
    <v-footer
      color="primary"
      app
    >
      <span class="white--text">&copy; TSIC CLOUD</span>
    </v-footer>
  </v-app>
</template>

<script>
import AdminService from './services/Admin';
  export default {
    name : 'App',
    data() {
     return {
        drawer : false,
        user : {}
        
     }; 
    },
    async mounted(){
      this.user = await AdminService.GetAuthUser();
    },
    methods : {
      changeLang() {
        this.$store.commit('changeLang');
        this.$vuetify.rtl = this.$store.state.language == 'ar';
      },
      getDir() {
        return this.$store.state.language == 'ar';
      }
    }
  }
</script>
<style lang="css">
@import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300&display=swap');

* {
    font-family: 'Tajawal', sans-serif;
}
.link {
  text-decoration: none;
}



</style>
