<template>
  <div>
    <progressStepper
      :step-active="stepActive"
      :previous-button="previous"
      :next-button="next"
    />

    <div class="page-inner-wrapper">
      <asideComponent />

      <form>
        <h1>
          <strong>Step One:</strong>&nbsp;User information
        </h1>

        <div class="wrapper">
          <div class="image-upload-wrapper">
            <span class="label">Profile Image</span>
            <div class="croppie-wrapper">
              <no-ssr>
                <vue-croppie
                  ref="croppieRef"
                  :enableExif="true"
                  :enableOrientation="true"
                  :enableResize="false"
                  :showZoomer="true"
                  @update="update"
                  :viewport="{ width: 180, height: 240 }"
                  :boundary="{ width: 180, height: 240 }" />
              </no-ssr>
            </div>

            <div class="image-wrapper">
              <svg v-if="cropped" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="check"><path fill="#FFFFFF" d="M413.505 91.951L133.49 371.966l-98.995-98.995c-4.686-4.686-12.284-4.686-16.971 0L6.211 284.284c-4.686 4.686-4.686 12.284 0 16.971l118.794 118.794c4.686 4.686 12.284 4.686 16.971 0l299.813-299.813c4.686-4.686 4.686-12.284 0-16.971l-11.314-11.314c-4.686-4.686-12.284-4.686-16.97 0z" class=""></path></svg>
              <img ref="croppedRef" v-if="cropped" v-bind:src="cropped">
            </div>

            <input type="file"
                   accept=".jpeg, .jpg, .png, .gif, .jp2, .jpx, .jpm, .tiff, .tiff-fx, .bmp, .x-bmp, .webp, .heif, .heic"
                   @change="updateCanvasImage"
                   ref="fileInput"
                   name="media">

            <div class="image-controls">
              <fn1-button-group>
                <fn1-button type="button"
                            class="image-input"
                            @click.native="$refs.fileInput.click()">
                  Upload
                </fn1-button>

                <fn1-button type="button"
                            v-if="full"
                            class="rotate"
                            @click.native="rotate(-90)">
                  Rotate
                </fn1-button>
              </fn1-button-group><br>

              <fn1-button-group>
                <fn1-button type="button"
                            v-if="full"
                            class="create"
                            @click.native="crop()">
                  Create
                </fn1-button>

                <fn1-button type="button"
                            v-if="full"
                            class="clear"
                            @click.native="clearImage()">
                  Clear
                </fn1-button>
              </fn1-button-group>
            </div>

            <span class="label">How to set a Profile Image:</span>
            <ol>
              <li>Click, <code class="upload">Upload Photo</code></li>
              <li>Adjust as desired</li>
              <li>Click, <code class="create">Create</code></li>
              <li>The <strong>Profile Image</strong> (on the right) is now set</li>
            </ol>
            <p><strong>note: </strong>The <strong>Original</strong> (left image) will remain as it was before editing any changes for the <strong>Profile Image</strong>.</p>
          </div>

          <div>
            <fn1-input v-model="first"
                     label="First Name *"
                     placeholder="First name"
                     name="first-name"
                     id="first-name" />

            <fn1-input v-model="middle"
                       label="Middle Name"
                       placeholder="Middle name"
                       name="middle-name"
                       id="middle-name" />

            <fn1-input v-model="last"
                       label="Last Name *"
                       placeholder="Last name"
                       name="last-name"
                       id="last-name" />

            <fn1-input v-model="nickname"
                       label="Nickname"
                       placeholder="Nickname"
                       name="nickname"
                       id="nickname" />

            <exampleSelect v-model="suffix"
                          label="Suffix"
                          name="suffix"
                          id="suffix"
                          :options="suffixOptions" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import {
  mapState,
  mapMutations,
  mapGetters,
  mapActions }          from 'vuex'
import {
  mapFields }           from 'vuex-map-fields'

import axios            from 'axios'

import progressStepper  from '~/components/progressStepper'
import asideComponent   from '~/components/asideComponent'
import exampleSelect    from '~/components/exampleSelect'

export default {
  layout:           'create',
  components: {
    progressStepper,
    asideComponent,
    exampleSelect
  },
  beforeUpdate() {
    this.$nextTick(() => {
      if(this.full){
        this.$refs.croppieRef.bind({
          url: this.full,
          zoom: 0,
          enableOrientation: true,
        });
      } else {
        this.$refs.croppieRef.bind({
          url: this.placeholderImg,
        });
      }
      if(this.cropped) {
        this.croppieCropped = this.cropped;
      }
    });
  },
  data() {
    return {
      stepActive: 1,
      next: { name: `create-two`},
      previous: false,
      suffixOptions: [
        { value: 'Jr.', text: 'Jr.' },
        { value: 'Sr.', text: 'Sr.' },
        { value: '2nd', text: '2nd' },
        { value: '3rd', text: '3rd' },
        { value: 'II',  text: 'II' },
        { value: 'III', text: 'III' },
        { value: 'IV',  text: 'IV' },
        { value: 'V',   text: 'V' },
        { value: 'VI',  text: 'VI' }
      ],
      blob:           '',
      placeholderImg: `./user-upload-placeholder.jpg`,
      imgOrientation: '',
      imgOrientation2: '',
      croppieCropped: null,
    }
  },
  computed: {
    ...mapFields([
      'auth.authUser',
      'groupLevels',
      'createUser.name.first',
      'createUser.name.middle',
      'createUser.name.last',
      'createUser.name.suffix',
      'createUser.name.nickname',
      'createUser.image.full',
      'createUser.image.cropped',
    ]),
  },
  methods: {
    crop() {
      let options = {
        enableResize: true,
        enableOrientation: true,
        type:   'base64',
        format: 'jpeg',
        size:   { width: 1150, height: 1533 }
      }
      this.$refs.croppieRef.result(options, (output) => {
        console.log(options);
        console.log(output);
        this.croppieCropped = output;
        this.cropped = output;
      });
    },
    clearImage() {
      this.croppieCropped      = null;
      this.full = "";
      this.cropped = "";
      this.$refs.croppieRef.bind({
        url: "",
        zoom: 0,
      });
      let canvas = this.$el.querySelector('.cr-image');
      const context = canvas.getContext('2d');
      context.clearRect(0, 0, canvas.width, canvas.height);
    },
    rotate(rotationAngle) {
      this.$refs.croppieRef.rotate(rotationAngle);
    },
    update(val) {
      console.log(val);
    },
    base64ToArrayBuffer (base64) {
        base64 = base64.replace(/^data\:([^\;]+)\;base64,/gmi, '');
        var binary_string =  window.atob(base64);
        var len = binary_string.length;
        var bytes = new Uint8Array( len );
        for (var i = 0; i < len; i++)        {
          bytes[i] = binary_string.charCodeAt(i);
        }
        return bytes.buffer;
    },
    updateCanvasImage(e) {
      let files     = e.target.files;
      let reader    = new FileReader();

      reader.onload = (e) => {
        let img = new Image();

        let self = this;
        img.onload = function() {
          self.getExif(img);
        }
        img.src = e.target.result;

        this.full = img.src;
        this.$refs.croppieRef.bind({
          url: this.full,
          zoom: 0,
          enableOrientation: true,
        });
      };
      reader.readAsDataURL(files[0]);
    },
    getExif(img) {
      let self = this;
      const EXIF     = require('exif-js');
      EXIF.getData(img, function() {
        self.imgOrientation = this.exifdata.Orientation;
        console.log(`getExif() :: ${self.imgOrientation}`);
      });
    }
  }
}
</script>

<style lang="scss" scoped>
  .image-upload-wrapper {
    display: flex;
    flex-wrap: wrap;
    border-right: 1px solid lighten($text-color, 50%);
    margin: 0 40px 0 0;
    align-items: flex-start;
    width: 480px;
    height: fit-content;

    .croppie-wrapper {
      width: 180px;
      margin: 0 40px 0 0;

      /deep/ .croppie-container {

        .cr-viewport  {
          position: relative;

          &:before {
            position: absolute;
            content: 'Original';
            font-size: $size-xs;
            font-weight: $weight-semi-bold;
            text-transform: uppercase;
            color: white;
            background-color: rgba(0,0,0,0.4);
            border-radius: $radius-default;
            padding: 5px 10px;
            bottom: 5px;
            right: 5px;
          }
        }

        .cr-viewport,
        .cr-resizer {
          border: 1px solid lighten($text-color, 50%);
        }

        .cr-slider-wrap {
          width: 180px;
          margin: 20px 0 0 0;
        }
      }
    }

    /deep/  ol, ul {
      margin: 10px 0;
      padding: 0 0 0 20px;

      /deep/ li {
        margin: 0 0 16px 0;
      }
    }

    code {
      &.upload {
        background-color: $text-color;
        position: relative;
        padding: 5px 10px 5px 40px;

        &:before {
          position: absolute;
          content: '';
          left: 10px;
          top: 6px;
          width: 20px;
          height: 15px;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 335.25 257.4373'%3E%3Ctitle%3EAsset 1%3C/title%3E%3Cg id='Layer_2' data-name='Layer 2'%3E%3Cg id='Layer_1-2' data-name='Layer 1'%3E%3Ccircle cx='167.625' cy='142.37726' r='67.71762' fill='none' stroke='%23FFFFFF' stroke-miterlimit='10' stroke-width='15'/%3E%3Cpath d='M317.794,249.9373H17.456c-5.47578,0-9.956-3.81411-9.956-8.47579V55.34736c0-4.66169,4.48018-8.47579,9.956-8.47579H83.575a17.40057,17.40057,0,0,0,14.13532-6.72763L108.914,16.37682a14.40256,14.40256,0,0,1,5.20835-6.06182,18.57288,18.57288,0,0,1,9.9401-2.815h87.30608a18.59964,18.59964,0,0,1,9.80785,2.73165,14.4177,14.4177,0,0,1,5.22238,5.90892l10.23946,20.762a17.57146,17.57146,0,0,0,14.62648,9.94025q.52673.02862,1.059.02872H317.794c5.47578,0,9.956,3.8141,9.956,8.47579V241.46151C327.75,246.12319,323.26982,249.9373,317.794,249.9373Z' fill='none' stroke='%23FFFFFF' stroke-miterlimit='10' stroke-width='15'/%3E%3Crect x='24.84969' y='19.88012' width='54.83301' height='7.5' fill='%23FFFFFF'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E%0A");
          background-position: contain;
          background-repeat: no-repeat;
        }
      }

      &.create {
        background-color: $color-green;
        position: relative;
        padding: 5px 10px 5px 35px;

        &:before {
          position: absolute;
          content: '';
          left: 10px;
          top: 6px;
          width: 20px;
          height: 15px;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' class='check'%3E%3Cpath fill='%23FFFFFF' d='M413.505 91.951L133.49 371.966l-98.995-98.995c-4.686-4.686-12.284-4.686-16.971 0L6.211 284.284c-4.686 4.686-4.686 12.284 0 16.971l118.794 118.794c4.686 4.686 12.284 4.686 16.971 0l299.813-299.813c4.686-4.686 4.686-12.284 0-16.971l-11.314-11.314c-4.686-4.686-12.284-4.686-16.97 0z' class=''%3E%3C/path%3E%3C/svg%3E");
          background-position: contain;
          background-repeat: no-repeat;
        }
      }
    }

    .image-wrapper {
      position: relative;
      margin-left: auto;
      padding: 0 40px 0 0;

      img {
        border: 1px solid lighten($text-color, 50%);
        width: 180px;
        height: 240px;
        margin: 0;
      }

      svg {
        &.check {
          position: absolute;
          display: block;
          background-color: $color-green;
          filter: drop-shadow(0 1px 1px lighten($text-color, 20%));
          height: 30px;
          width: 30px;
          padding: 5px;
          border-radius: 50%;
          top: -10px;
          right: 30px;
        }
      }
    }

    input {
      &[type=file] {
        @include visuallyHidden;
      }
    }

    .image-controls {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      // background-color: blue;
      border-top: 1px solid lighten($text-color, 50%);
      border-bottom: 1px solid lighten($text-color, 50%);
      width: 100%;
      margin: 20px 0;
    }

    .button-group {
      // background-color: red;
      margin: 0;
      padding: 20px 20px 20px 0;

      &:last-of-type {
        margin-left: auto;
      }

      button {
        display: inline-flex;
        flex-direction: row;
        color: white;
        background-color: $text-color;
        border-color: transparent;
        // margin: 0 20px 0 0;
        padding: 5px 10px;
        font-weight: $weight-normal;
        // font-size: 18px;
        line-height: 18px;

        &:hover {
          background: darken($text-color, 10%);
        }

        &.image-input {
          position: relative;
          padding: 5px 10px 5px 40px;

          &:before {
            position: absolute;
            content: '';
            left: 10px;
            top: 6px;
            width: 20px;
            height: 15px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 335.25 257.4373'%3E%3Ctitle%3EAsset 1%3C/title%3E%3Cg id='Layer_2' data-name='Layer 2'%3E%3Cg id='Layer_1-2' data-name='Layer 1'%3E%3Ccircle cx='167.625' cy='142.37726' r='67.71762' fill='none' stroke='%23FFFFFF' stroke-miterlimit='10' stroke-width='15'/%3E%3Cpath d='M317.794,249.9373H17.456c-5.47578,0-9.956-3.81411-9.956-8.47579V55.34736c0-4.66169,4.48018-8.47579,9.956-8.47579H83.575a17.40057,17.40057,0,0,0,14.13532-6.72763L108.914,16.37682a14.40256,14.40256,0,0,1,5.20835-6.06182,18.57288,18.57288,0,0,1,9.9401-2.815h87.30608a18.59964,18.59964,0,0,1,9.80785,2.73165,14.4177,14.4177,0,0,1,5.22238,5.90892l10.23946,20.762a17.57146,17.57146,0,0,0,14.62648,9.94025q.52673.02862,1.059.02872H317.794c5.47578,0,9.956,3.8141,9.956,8.47579V241.46151C327.75,246.12319,323.26982,249.9373,317.794,249.9373Z' fill='none' stroke='%23FFFFFF' stroke-miterlimit='10' stroke-width='15'/%3E%3Crect x='24.84969' y='19.88012' width='54.83301' height='7.5' fill='%23FFFFFF'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E%0A");
            background-position: contain;
            background-repeat: no-repeat;
          }
        }

        &.rotate {
          border-left: 1px solid darken($text-color, 15%);
          position: relative;
          padding: 5px 10px 5px 35px;

          &:before {
            position: absolute;
            content: '';
            left: 10px;
            top: 6px;
            width: 20px;
            height: 15px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ffffff' d='M20 8h10c6.627 0 12 5.373 12 12v110.625C85.196 57.047 165.239 7.715 256.793 8.001 393.18 8.428 504.213 120.009 504 256.396 503.786 393.181 392.834 504 256 504c-63.926 0-122.202-24.187-166.178-63.908-5.113-4.618-5.354-12.561-.482-17.433l7.069-7.069c4.503-4.503 11.749-4.714 16.482-.454C150.782 449.238 200.935 470 256 470c117.744 0 214-95.331 214-214 0-117.744-95.331-214-214-214-82.862 0-154.737 47.077-190.289 116H180c6.627 0 12 5.373 12 12v10c0 6.627-5.373 12-12 12H20c-6.627 0-12-5.373-12-12V20c0-6.627 5.373-12 12-12z' class=''%3E%3C/path%3E%3C/svg%3E");
            background-position: contain;
            background-repeat: no-repeat;
          }
        }

        &.create {
          background-color: $color-green;
          position: relative;
          padding: 5px 10px 5px 35px;

          &:hover {
            background-color: darken($color-green, 5%);
          }

          &:before {
            position: absolute;
            content: '';
            left: 10px;
            top: 6px;
            width: 20px;
            height: 15px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512' class='check'%3E%3Cpath fill='%23FFFFFF' d='M413.505 91.951L133.49 371.966l-98.995-98.995c-4.686-4.686-12.284-4.686-16.971 0L6.211 284.284c-4.686 4.686-4.686 12.284 0 16.971l118.794 118.794c4.686 4.686 12.284 4.686 16.971 0l299.813-299.813c4.686-4.686 4.686-12.284 0-16.971l-11.314-11.314c-4.686-4.686-12.284-4.686-16.97 0z' class=''%3E%3C/path%3E%3C/svg%3E");
            background-position: contain;
            background-repeat: no-repeat;
          }
        }

        &.clear {
          background-color: $color-orange-darker;
          position: relative;
          padding: 5px 10px 5px 30px;

          &:hover {
            background-color: darken($color-orange-darker, 5%);
          }

          &:before {
            position: absolute;
            content: '';
            left: 10px;
            top: 6px;
            width: 20px;
            height: 15px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'%3E%3Cpath fill='%23FFFFFF' d='M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z' class=''%3E%3C/path%3E%3C/svg%3E");
            background-position: contain;
            background-repeat: no-repeat;
          }
        }
      }
    }

    ul {
      display: block;
      margin: 10px 0;
      padding: 10px;
      border-radius: 4px;
      background-color: rgba(225,225,225,0.1);

      li {
        display: inline-block;
        margin: 0;

        img {
          cursor: pointer;
          margin: 0 0 5px 0;
          // transform: scaleX(-1);
          // width: 100px;
          // height: 75px;
        }

        button {
          text-align: center;
          margin: 0 auto !important;
          display: block;
          width: 100%;
        }
      }
    }
  }
</style>