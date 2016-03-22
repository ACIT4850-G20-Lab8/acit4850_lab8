<div class="row">
    <h2>Three facets</h2>

    {orders}
    <p><a href="/welcome/order/{filename}">{filename}</a></p>
    {/orders}

    <p>Select day and time</p>

</div>
<!--  drop down menu for search query -->

<div>
    <!--time-->
    <form method="post">
        <select name="selectedtime" ><!--onchange="this.form.submit()">-->
            <option value="" {selected}> - -</option>
            {times}
            <option value="{time}" {selected}>{time}</option>
            {/times}
        </select>
    </form>
    <!--day-->
    <form method="post">
        <select name="selectedday" >
            <option value="{day}" {selected}> - -</option>
            {days}
            <option value="{day}" {selected}>{day}</option>
            {/days}
        </select>
        <input type="button" value="submit" onclick="this.form.submit()"/>
    </form>
</div>