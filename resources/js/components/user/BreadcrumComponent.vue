<template>
<div class="row page-header">
<div class="col-lg-6 align-self-center ">
 <h2 >{{ title }}</h2>
    <ol class="breadcrumb">
      <li
        v-for="(breadcrumb, idx) in breadcrumbList"
        :key="idx"
        @click="routeTo(idx)"
        :class=" {'breadcrumb-item': !!breadcrumb.link}">
        {{ breadcrumb.name }}
      </li>
   </ol>
  </div>
  </div> 
 
</template>
<script>
export default{
   name: 'Breadcrumb',
   data () {
    return {
      breadcrumbList: [],
      title:''
    }
  },
  mounted () { 
    this.updateList() 
  },
  watch: { '$route' () { 
      this.updateList()
      } 
  },
  methods: {
    routeTo (pRouteTo) {
      if (this.breadcrumbList[pRouteTo].link) this.$router.push(this.breadcrumbList[pRouteTo].link)
    },
    updateList () { 
      this.breadcrumbList = this.$route.meta.breadcrumb,
        this.title = this.$route.meta.title

      }
  }
	
}
</script>

