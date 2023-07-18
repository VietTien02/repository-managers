<form method="post" action="<?php $_SERVER['REQUEST_URI'] ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="input-group" style="width : 550px">
                <input type="date" class="form-control" id="filter_date" name="filter_date">
                <select class="form-select" id="filter_month" name="filter_month">
                    <option value="">Chọn tháng</option>
                    <option value="01">Tháng 1</option>
                    <option value="02">Tháng 2</option>
                    <option value="03">Tháng 3</option>
                    <option value="04">Tháng 4</option>
                    <option value="05">Tháng 5</option>
                    <option value="06">Tháng 6</option>
                    <option value="07">Tháng 7</option>
                    <option value="08">Tháng 8</option>
                    <option value="09">Tháng 9</option>
                    <option value="10">Tháng 10</option>
                    <option value="11">Tháng 11</option>
                    <option value="12">Tháng 12</option>
                </select>
                <select class="form-select" id="filter_year" name="filter_year">
                    <option value="">Chọn năm</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                </select>
                <button type="submit" name="filter_button" class="btn btn-primary">Lọc</button>
            </div>
        </div>
    </div>
</form>