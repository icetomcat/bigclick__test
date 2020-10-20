<?php

namespace Database\Seeders;

use App\Models\NestedSet;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    static public function randomWords($text = '')
    {
        $loremArray = explode(' ', $text);
        $shuffled = [];
        while (true) {
            if (count($shuffled) === 0) {
                $shuffled = $loremArray;
                shuffle($shuffled);
            }
            yield array_pop($shuffled);
        }
    }

    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $lorem = 'eget magna fermentum iaculis non diam phasellus vestibulum lorem sed risus ultricies tristique nulla aliquet enim tortor auctor urna nunc cursus metus aliquam eleifend nulla posuere sollicitudin aliquam ultrices sagittis orci scelerisque purus semper eget duis tellus urna condimentum mattis pellentesque nibh tortor aliquet lectus proin nibh nisl condimentum venenatis condimentum vitae sapien pellentesque habitant morbi tristique senectus netus malesuada fames turpis egestas sed tempus urna pharetra pharetra massa massa ultricies quis hendrerit dolor magna eget est lorem ipsum dolor sit amet consectetur adipiscing elit pellentesque habitant morbi tristique senectus netus malesuada fames turpis egestas integer eget aliquet nibh praesent tristique magna sit amet purus gravida quis blandit turpis cursus hac habitasse platea dictumst quisque sagittis purus sit amet volutpat consequat mauris nunc congue nisi vitae suscipit tellus mauris diam maecenas sed enim sem viverra aliquet eget sit amet tellus cras adipiscing enim turpis egestas pretium aenean pharetra magna placerat vestibulum lectus mauris ultrices eros cursus turpis massa tincidunt dui ornare lectus sit amet est placerat egestas erat imperdiet sed euismod nisi porta lorem mollis aliquam porttitor leo diam sollicitudin tempor nisl nunc ipsum faucibus vitae aliquet nec ullamcorper sit amet risus nullam eget felis eget nunc lobortis mattis aliquam faucibus purus massa tempor nec feugiat nisl pretium fusce velit tortor pretium viverra suspendisse potenti nullam tortor vitae purus faucibus ornare suspendisse sed nisi lacus sed viverra tellus hac habitasse platea dictumst vestibulum rhoncus est pellentesque elit ullamcorper dignissim cras tincidunt lobortis feugiat vivamus augue eget arcu dictum varius duis consectetur lorem donec massa sapien faucibus molestie feugiat sed lectus vestibulum mattis ullamcorper velit sed ullamcorper morbi tincidunt ornare massa eget egestas purus viverra accumsan nisl nisi scelerisque ultrices vitae auctor augue lectus arcu bibendum varius vel pharetra vel turpis nunc eget lorem dolor sed viverra ipsum nunc aliquet bibendum enim facilisis gravida neque convallis cras semper auctor neque vitae tempus quam pellentesque nec nam aliquam sem tortor consequat porta nibh venenatis cras sed felis eget velit aliquet sagittis consectetur purus faucibus pulvinar elementum integer enim neque volutpat tincidunt vitae semper quis lectus nulla volutpat diam venenatis tellus metus vulputate scelerisque felis imperdiet proin fermentum leo vel orci porta non pulvinar neque laoreet suspendisse interdum consectetur libero faucibus nisl tincidunt eget nullam non nisi est sit amet facilisis magna etiam tempor orci lobortis elementum nibh tellus molestie nunc non blandit massa enim nec dui nunc mattis enim tellus elementum sagittis vitae leo duis diam quam nulla porttitor massa neque aliquam vestibulum morbi blandit cursus risus ultrices tempus imperdiet nulla malesuada pellentesque elit eget gravida cum sociis natoque penatibus magnis dis parturient montes nascetur ridiculus';
        $items = [];

        foreach (DatabaseSeeder::randomWords($lorem) as $word) {
            if (count($items) >= 500) break;
            $items[] = ['title' => $word, 'children' => []];
        }

        while (count($items) > 1) {
            $children = array_splice($items, 0, min(random_int(10, 20), count($items) - 1));
            $items[random_int(0, count($items) - 1)]['children'] = array_merge($items[random_int(0, count($items) - 1)]['children'], $children);
        }
        $items = $items[0];

        /** @var $node NestedSet */
        $node = NestedSet::create($items);

        $node->save();
    }
}
