<template>
  <div class="app-container">
    <upload-excel-component
    @uploaded-file="uploadFile"
    :on-success="handleSuccess"
    :before-upload="beforeUpload"
    />
  </div>
</template>

<script>
  import UploadExcelComponent from '@/components/UploadExcel/index.vue';
  import Resource from '@/api/resource';
  const fileResource = new Resource('file-export');

  export default {
    name: 'UploadExcel',
    components: { UploadExcelComponent },
    data() {
      return {
        tableData: [],
        tableHeader: [],
      };
    },
    methods: {
      uploadFile(file) {
        let formData = new FormData();
        formData.append('file', file);
        let self = this;

        let result = fileResource.store(formData).then(response => {
          self.$message({
            message: 'Data imported successfully',
            type: 'success',
          });
        });
      },
      beforeUpload(file) {
        const isLt1M = file.size / 1024 / 1024 < 1;

        if (isLt1M) {
          return true;
        }

        this.$message({
          message: 'Please do not upload files larger than 1m in size.',
          type: 'warning',
        });
        return false;
      },
      handleSuccess({ results, header }) {
        this.tableData = results;
        this.tableHeader = header;
      },
    },
  };
</script>
