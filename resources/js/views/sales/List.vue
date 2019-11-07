<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="query.keyword"
        :placeholder="$t('table.keyword')"
        style="width: 200px;"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />

      <!-- 
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>

      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
   

      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
    -->

      <el-select
        v-model="query.funding_status"
        :placeholder="'Funding'"
        clearable
        style="margin-left:10px; width: 150px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in fundingOptions"
          :key="item.id"
          :label="item.name | uppercaseFirst"
          :value="item.id"
        />
      </el-select>

      <el-select
        v-model="query.shipped_record"
        :placeholder="'Shipped'"
        clearable
        style="margin-left:10px; width: 150px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in shippedOptions"
          :key="item.key"
          :label="item.label | uppercaseFirst"
          :value="item.key"
        />
      </el-select>

      <el-select
        v-model="query.local_or_state"
        :placeholder="'Local / State'"
        clearable
        style="margin-left:10px; width: 150px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in ['local', 'state']"
          :key="item"
          :label="item | uppercaseFirst"
          :value="item"
        />
      </el-select>

      <el-select
        v-model="query.cash_finance"
        :placeholder="'Cash / Finance'"
        clearable
        style="margin-left:10px; width: 150px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in ['cash', 'finance']"
          :key="item"
          :label="item.replace('_', ' ') | uppercaseFirst"
          :value="item"
        />
      </el-select>

      <el-select
        v-model="query.location"
        :placeholder="'Location'"
        clearable
        style="margin-left:10px; width: 150px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in ['gone', 'lot', 'on_lot']"
          :key="item"
          :label="item.replace('_', ' ') | uppercaseFirst"
          :value="item"
        />
      </el-select>

      <el-select
        v-model="query.sales_status"
        :placeholder="'Sales Status'"
        clearable
        style="margin-left:10px; width: 150px"
        class="filter-item"
        @change="handleFilter"
      >
        <el-option
          v-for="item in ['active', 'done', 'cancel']"
          :key="item"
          :label="item.replace('_', ' ') | uppercaseFirst"
          :value="item"
        />
      </el-select>

      <el-input
        type="date"
        style="margin-left:10px; width: 150px"
        placeholder="File Uploaded"
        v-model="query.file_uploaded_at"
        @change="handleFilter"
      >
      </el-input>
    </div>

    <el-table
      v-loading="loading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="VIN No.">
        <template slot-scope="scope">
          <span>
            {{ scope.row.vin.substr(0, scope.row.vin.length - 4) }}
            <strong>{{ scope.row.vin.substr(-4) }}</strong>
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Model No.">
        <template slot-scope="scope">
          <span>{{ scope.row.model }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Year">
        <template slot-scope="scope">
          <span>{{ scope.row.year }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="User">
        <template slot-scope="scope">
          <span>{{ scope.row.user ? scope.row.user.name : 'N/A' }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Need To Address">
        <template slot-scope="scope">
          <span>{{
            scope.row.need_to_address ? scope.row.need_to_address : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Trade In">
        <template slot-scope="scope">
          <span>{{ scope.row.trade_in ? scope.row.trade_in : 'N/A' }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Deposit">
        <template slot-scope="scope">
          <span>{{ scope.row.deposit ? scope.row.deposit : 'N/A' }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Down Payment">
        <template slot-scope="scope">
          <span>{{
            scope.row.down_payment ? scope.row.down_payment : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Local / State">
        <template slot-scope="scope">
          <span>{{
            scope.row.local_or_state
              ? scope.row.local_or_state.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Cash Finance">
        <template slot-scope="scope">
          <span>{{
            scope.row.cash_finance
              ? scope.row.cash_finance.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Location">
        <template slot-scope="scope">
          <span>{{
            scope.row.location
              ? scope.row.location.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Shipped">
        <template slot-scope="scope">
          <span>{{
            scope.row.shipped
              ? scope.row.shipped.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Signed Record">
        <template slot-scope="scope">
          <span>{{
            scope.row.signed_record
              ? scope.row.signed_record.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Lender">
        <template slot-scope="scope">
          <span>{{
            scope.row.lender_value
              ? scope.row.lender_value.name.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Funding Status">
        <template slot-scope="scope">
          <span>{{
            scope.row.funding_status
              ? scope.row.funding_status.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Warranty">
        <template slot-scope="scope">
          <span>{{
            scope.row.warranty_value
              ? scope.row.warranty_value.name.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="State Inspection">
        <template slot-scope="scope">
          <span>{{
            scope.row.inspection
              ? scope.row.inspection.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Parts Needed">
        <template slot-scope="scope">
          <span>{{
            scope.row.parts_needed
              ? scope.row.parts_needed.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Make Ready">
        <template slot-scope="scope">
          <span>{{
            scope.row.make_ready_value
              ? scope.row.make_ready_value.name.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Repair Status">
        <template slot-scope="scope">
          <span>{{
            scope.row.repair_status
              ? scope.row.repair_status.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Ship Date">
        <template slot-scope="scope">
          <span>{{
            scope.row.ship_date
              ? new Date(scope.row.ship_date).toLocaleDateString()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Notes">
        <template slot-scope="scope">
          <span>{{
            scope.row.notes
              ? scope.row.notes.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Docs Needed">
        <template slot-scope="scope">
          <span>{{
            scope.row.docs_needed
              ? scope.row.docs_needed.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Review">
        <template slot-scope="scope">
          <span>{{
            scope.row.review
              ? scope.row.review.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Sales Status">
        <template slot-scope="scope">
          <span>{{
            scope.row.sales_status
              ? scope.row.sales_status.replace('_', ' ').toUpperCase()
              : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="File Uploaded At">
        <template slot-scope="scope">
          <span>{{
            scope.row.file_uploaded_at ? scope.row.file_uploaded_at : 'N/A'
          }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="200">
        <template slot-scope="scope">
          <el-button
            @click.prevent="viewSalesDetail(scope.row)"
            type="primary"
            size="small"
          >
            View
          </el-button>

          <el-button
            @click.prevent="editSalesDetail(scope.row)"
            type="info"
            size="small"
          >
            Edit
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :visible.sync="dialogEditOpen" title="Edit Sales">
      <div v-loading="currentItemUpdate" class="form-container">
        <div v-if="currentItem" class="permissions-container">
          <el-form
            ref="userForm"
            :rules="rules"
            :model="currentItem"
            label-position="left"
            label-width="150px"
            style="max-width: 500px;"
          >
            <el-form-item label="Need To Address">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.need_to_address"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Trade In">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.trade_in"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Deposit">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.deposit"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Down Payment">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.down_payment"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Local / State">
              <el-select
                v-model="currentItem.state_or_local"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['local', 'state']"
                  :key="item"
                  :label="item.toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Cash / Finance">
              <el-select
                v-model="currentItem.cash_finance"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['cash', 'finance']"
                  :key="item"
                  :label="item.toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Location">
              <el-select
                v-model="currentItem.location"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['gone', 'lot', 'on_lot']"
                  :key="item"
                  :label="item.replace('_', ' ').toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Shipped">
              <el-select
                v-model="currentItem.shipped"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in shippedOptions"
                  :key="item.key"
                  :label="item.label.replace('_', ' ').toUpperCase()"
                  :value="item.key"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Signed Record">
              <el-select
                v-model="currentItem.signed_record"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['yes', 'no', 'pending']"
                  :key="item"
                  :label="item.replace('_', ' ').toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Lender">
              <el-select
                v-model="currentItem.lender"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in lenders"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Funding Status">
              <el-select
                v-model="currentItem.funding_status"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in fundingOptions"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Warranty">
              <el-select
                v-model="currentItem.warranty"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in warranties"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Parts Needed">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.parts_needed"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="State Inspection">
              <el-select
                v-model="currentItem.inspection"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in ['yes', 'no', 'n/a']"
                  :key="item"
                  :label="item.replace('_', ' ').toUpperCase()"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Make Ready">
              <el-select
                v-model="currentItem.make_ready"
                class="filter-item"
                placeholder="Please select"
              >
                <el-option
                  v-for="item in make_ready"
                  :key="item.id"
                  :label="item.name.replace('_', ' ').toUpperCase()"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item label="Repair Status">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.repair_status"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Ship Date">
              <el-input
                type="date"
                placeholder="Please input"
                v-model="currentItem.ship_date"
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Notes">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.notes"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Docs Needed">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.docs_needed"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Review">
              <el-input
                type="textarea"
                placeholder="Please input"
                v-model="currentItem.review"
                show-word-limit
              >
              </el-input>
            </el-form-item>

            <el-form-item label="Sales Status">
              <el-select
                v-model="currentItem.sales_status"
                :placeholder="'Sales Status'"
                clearable
                style="margin-left:10px; width: 150px"
                class="filter-item"
              >
                <el-option
                  v-for="item in ['active', 'done', 'cancel']"
                  :key="item"
                  :label="item.replace('_', ' ') | uppercaseFirst"
                  :value="item"
                />
              </el-select>
            </el-form-item>

            <div class="clear-left" />

            <el-button
              @click.prevent="updatePurchase()"
              type="info"
              size="small"
            >
              Update
            </el-button>
          </el-form>
        </div>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogOpen" title="View Sales">
      <div class="form-container">
        <div class="permissions-container">
          <el-form v-if="currentItem" ref="form" label-width="320px">
            <el-form-item
              :key="index"
              v-for="(listValue, index) in listKeys"
              :label="listValue.label"
            >
              {{ currentItem[listValue.key] }}
            </el-form-item>
          </el-form>
        </div>
      </div>
    </el-dialog>

    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
import PurchaseResource from '@/api/purchase';
import waves from '@/directive/waves'; // Waves directive

const purchaseResource = new PurchaseResource();
const warrantyResource = new Resource('warranty');
const fundingResource = new Resource('funding-status');
const lenderResource = new Resource('lender');
const makeReadyResource = new Resource('make-ready');

const moment = require('moment');

export default {
  name: 'UserList',
  components: { Pagination },
  data() {
    return {
      dialogOpen: false,
      listKeys: [
        {
          key: 'vin',
          label: 'Vin',
        },
        {
          key: 'model',
          label: 'Model',
        },
        {
          key: 'year',
          label: 'Year',
        },
        {
          key: 'make',
          label: 'Make',
        },
        {
          key: 'need_to_address',
          label: 'Need To Address',
        },
        {
          key: 'deposit',
          label: 'Deposit',
        },
        {
          key: 'down_payment',
          label: 'Down Payment',
        },
        {
          key: 'local_or_state',
          label: 'Local / State',
        },
        {
          key: 'cash_finance',
          label: 'Cash / Finance',
        },
        {
          key: 'location',
          label: 'Location',
        },
        {
          key: 'shipped',
          label: 'Shipped',
        },
        {
          key: 'signed_record',
          label: 'Signed Record',
        },
        {
          key: 'funding_status',
          label: 'Funding Status',
        },
        {
          key: 'warranty',
          label: 'Warranty',
        },
        {
          key: 'inspection',
          label: 'State Inspection',
        },

        {
          key: 'parts_needed',
          label: 'Parts Needed',
        },
        {
          key: 'make_ready',
          label: 'Make Ready',
        },
        {
          key: 'repair_status',
          label: 'Repair Status',
        },
      ],
      fundingOptions: [],
      shippedOptions: [
        {
          key: 'pu',
          label: 'P/U',
        },
        {
          key: 'ship_cop',
          label: 'Ship COP',
        },
        {
          key: 'ship_cod',
          label: 'Ship COD',
        },
        {
          key: 'picked_up',
          label: 'Picked Up',
        },
        {
          key: 'pick_up',
          label: 'Pick Up',
        },
      ],
      currentItemUpdate: false,
      make_ready: [],
      warranties: [],
      lenders: [],
      dialogEditOpen: false,
      currentItem: null,
      users: [],
      purchaseItem: {},
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      userCreating: false,
      query: {
        pagination: true,
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
        is_sold: 1,
      },
      newItem: {},
      dialogFormVisible: false,
      dialogPurchase: false,
      dialogPurchaseUpdate: false,
      dialogPermissionLoading: false,
      currentItemId: 0,
      rules: {},
    };
  },
  computed: {},
  created() {
    this.resetCurrentItem();
    this.getList();
    this.getOptions();
  },
  methods: {
    getOptions() {
      this.getWarranties();
      this.getFundings();
      this.getLenders();
      this.getMakeReady();
    },
    async getMakeReady() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await makeReadyResource.list(this.query);
      this.make_ready = response.data;

      this.make_ready.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    async getWarranties() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await warrantyResource.list(this.query);
      this.warranties = response.data;
      console.log(this.warranties, 222);
      this.warranties.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    async getFundings() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await fundingResource.list(this.query);
      this.fundingOptions = response.data;
      this.fundingOptions.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    async getLenders() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await lenderResource.list(this.query);
      this.lenders = response.data;
      this.lenders.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },

    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const response = await purchaseResource.list(this.query);
      this.list = response.data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = response.pagination.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetCurrentItem();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    async editSalesDetail(currentItem) {
      this.currentItem = Object.assign({}, currentItem);

      if (this.currentItem.ship_date) {
        this.currentItem.ship_date = moment(this.currentItem.ship_date).format(
          'YYYY-MM-DD'
        );
      }

      console.log(this.currentItem, 2312321);

      this.dialogEditOpen = true;
    },
    async viewSalesDetail(currentItem) {
      this.dialogOpen = true;
      this.currentItem = currentItem;
    },
    async handlePurchaseUpdate(id, purchaseItem) {
      this.purchaseItem = purchaseItem;
      this.currentItemId = id;
      this.dialogPermissionLoading = true;
      this.dialogPurchaseUpdate = true;
      this.dialogPermissionLoading = false;
    },
    updatePurchase() {
      this.currentItemUpdate = true;
      this.$refs['userForm'].validate(valid => {
        if (valid) {
          this.currentItem.is_sold = 1;
          purchaseResource
            .update(this.currentItem.id, this.currentItem)
            .then(response => {
              this.$message({
                message: 'Sale has been updated successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.currentItemUpdate = false;
              this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.currentItemUpdate = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetCurrentItem() {
      this.purchaseItem = {};
      this.currentItemId = 0;
      this.dialogPurchase = false;
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
  },
};
</script>

<style lang="scss" scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
.dialog-footer {
  text-align: left;
  padding-top: 0;
  margin-left: 150px;
}
.app-container {
  flex: 1;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
  .block {
    float: left;
    min-width: 250px;
  }
  .clear-left {
    clear: left;
  }
}
</style>
