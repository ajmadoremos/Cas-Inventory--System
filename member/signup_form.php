<div class="container-fluid">
  <div class="panel">
    <div class="panel-body">

      <!-- Role Selection Buttons -->
      <div class="row text-center">
        <div class="col-md-4 col-md-offset-2">
          <div class="panel panel-blue student-btn" style="cursor: pointer;">
            <div class="panel-body">
              <h4 class="color-white">
                <i class="fa fa-user fa-2x"></i> &nbsp; I'M A STUDENT
              </h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-blue faculty-btn" style="cursor: pointer;">
            <div class="panel-body">
              <h4 class="color-white">
                <i class="fa fa-graduation-cap fa-2x"></i> &nbsp; I'M A FACULTY
              </h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Signup Forms -->
      <div class="row">
        <div class="col-md-6 col-md-offset-3">

          <!-- Student Form -->
          <form class="frm_student_signs" id="student-form">
            <h4>Sign up - Student</h4>
            <hr>
            <div class="form-group">
              <label>School ID Number</label>
              <input type="text" name="sid_number" class="form-control" placeholder="e.g. 21-1-1-0221" required maxlength="14">
            </div>
            <div class="form-group">
              <label>First Name</label>
              <input type="text" name="s_fname" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" name="s_lname" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="s_gender" class="form-control" required>
                <option disabled selected>Your gender</option>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
            <div class="form-group">
              <label>Contact Number</label>
              <input type="number" name="s_contact" class="form-control" pattern="^09\d{9}$" maxlength="11" placeholder="e.g. 09091234567" required>
              <small class="text-muted">Format: 11-digit number (e.g. 09091234567)</small>
            </div>
            <div class="form-group">
              <label>Department</label>
              <select name="s_department" class="form-control" required>
                <option disabled selected>Select department</option>
                <option>AB</option>
                <option>BEED</option>
                <option>BSED</option>
                <option>BSCE</option>
                <option>BSHRM</option>
                <option>BSIS</option>
                <option>BSIT</option>
              </select>
            </div>
            <div class="form-group">
              <label>Year</label>
              <select name="s_year" class="form-control" required>
                <option disabled selected>Select year</option>
                <option>1st</option>
                <option>2nd</option>
                <option>3rd</option>
                <option>4th</option>
              </select>
            </div>
            <div class="form-group">
              <label>Section</label>
              <input type="text" name="s_section" class="form-control" required>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-success btn_student">Sign up</button><br>
            </div>
          </form>

          <!-- Faculty Form -->
          <form class="frm_faculty_sign" id="faculty-form" style="display: none;">
            <h4>Sign up - Faculty</h4>
            <hr>
            <div class="form-group">
              <label>School ID Number</label>
              <input type="number" name="f_id" class="form-control" required>
            </div>
            <div class="form-group">
              <label>First Name</label>
              <input type="text" name="f_fname" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" name="f_lname" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="f_gender" class="form-control" required>
                <option disabled selected>Your gender</option>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
            <div class="form-group">
              <label>Contact Number</label>
              <input type="tels" name="f_contact" class="form-control" pattern="^09\d{9}$" maxlength="11" placeholder="e.g. 09091234567" required>
              <small class="text-muted">Format: 11-digit number (e.g. 09091234567)</small>
            </div>
            <div class="form-group">
              <label>Department</label>
              <select name="f_department" class="form-control" required>
                <option disabled selected>Select department</option>
                <option>AB</option>
                <option>BEED</option>
                <option>BSED</option>
                <option>BSCE</option>
                <option>BSHRM</option>
                <option>BSIS</option>
                <option>BSIT</option>
              </select>
            </div>
            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary btn_faculty">Sign up</button><br>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
