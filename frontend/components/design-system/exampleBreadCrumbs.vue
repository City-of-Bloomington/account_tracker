<template>
  <component
    :is="type"
    class="breadcrumbs"
    v-if="breadCrumbs.length">
    <li v-for="crumb, i in breadCrumbs">
      <nuxt-link
        v-if="i < breadCrumbs.length-1"
        :to="crumb.path">{{ crumb.name }}
      </nuxt-link>

      <span
        v-else
        class="last"
        style="cursor: default">{{ crumb.name }}
      </span>
    </li>
  </component>
</template>

<script>
export default {
  name:         "exampleBreadCrumbs",
  status:       "ready",
  release:      "1.0.0",
  props: {
    type: {
      type:     String,
      default:  "ul",
      validator: value => {
        return   value.match(/(ul)/)
      }
    },
    homeCrumb: {
      type:     String,
      default:  "Home"
    },
  },
  computed: {
		breadCrumbs() {
      let path    = '',
          crumbs  = [{ name: this.homeCrumb, path: '/' }],
          route   = (this.$route.path                        ).split('/'),
          matched = (this.$route.matched[0].meta.crumbs || '').split('/');
      
      if(!this.$route) return [];

			for(var i = 1; i < route.length; i++) {
        let name = (matched[i] || route[i]);
        
        // if '', skip
        if(route[i] == '') continue;

				// this.homeCrumb += ' : '+ name;
				          path += '/'  + name;
		
				crumbs.push({ name: name, path: path });
			}

			// window.document.title = this.homeCrumb;

			return crumbs;
		}
  }
}
</script>

<style lang="scss">
  .breadcrumbs {
    background-color: $color-blue;
    padding: 8px 20px;
    width: calc(100% + 40px);
    transform: translateX(-20px);

    li {
      position: relative;
      margin: 0 15px 0 0;
      padding: 0;
      display: inline-flex;

      a, span {
        font-weight: $weight-semi-bold;
        font-size: 14px;
        color: white;
        line-height: inherit;
      }

      &:last-of-type {
        &:after {
          display: none;
        }
      }

      &:after {
        // content: '\276D';
        content: '\203A';
        color: white;
        position: absolute;
        right: -10px;
      }
    }
  }
</style>