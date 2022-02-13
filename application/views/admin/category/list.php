<?php $this->load->view('admin/header');?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">

            <?php if($this->session->flashdata('success') != ""){?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success');?></div>
            <?php } ?>

						<?php if($this->session->flashdata('error') != ""){?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error');?></div>
            <?php } ?>
            
            <div class="card">
              <div class="card-header">
                  <div class="card-title">
                      <form id="searhFrm" name="searhFrm" method="get" action="">
                          <div class="input-group mb-0">
                              <input type="text" value="<?php echo $queryString;?>" class="form-control" placeholder="Search" name="q">
                              <div class="input-group-append">
                                  <button class="input-group-text" id="basic-addon1">
                                      <i class="fas fa-search"></i>
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="card-tools">
                      <a href="<?php echo base_url().'admin/category/create';?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create</a>
                  </div>
              </div>
              <div class="card-body">
                  <table class="table">
                      <tr>
                          <th width="50">#</th>
                          <th>Name</th>
                          <th width="100">Status</th>
                          <th width="180" class="text-center">Action</th>
                      </tr>


					  <?php if(!empty($categories)){?>
						<?php foreach ($categories as $categoryRow){?>
                      <tr>
                          <td><?php echo $categoryRow['id'];?></td>
                          <td><?php echo $categoryRow['name'];?></td>
                          <td>
							  <?php if($categoryRow['status'] == 1){?>
                        <span class="badge badge-success">Active</span>
							  <?php } else{?>
												<span class="badge badge-danger">Block</span>
							  <?php } ?>
                          </td>
                          <td class="text-center">
                              <a href="<?php echo base_url().'admin/Category/edit/'.$categoryRow['id'];?>" class="btn btn-primary btn-sm">
                                  <i class="far fa-edit"></i> Edit
                             </a>
                             <a href="javascript:void(0);" onclick="deleteCategory(<?php echo $categoryRow['id'];?>)" class="btn btn-danger btn-sm">
                                  <i class="far fa-trash-alt"></i> Delete
                              </a>
                          </td>
                      </tr>
				   <?php } ?>
					  <?php } else {?>
						<tr>
							<td colspan="4">Records not found</td>
						</tr>

					  <?php } ?>
                  </table>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php $this->load->view('admin/footer');?>
<script type="text/javascript">
    function deleteCategory(id) {
        if(confirm("Are you sure you want to delete category?")) {
            window.location.href='<?php echo base_url().'admin/category/delete/';?>'+id;
        } 
    }
</script>
