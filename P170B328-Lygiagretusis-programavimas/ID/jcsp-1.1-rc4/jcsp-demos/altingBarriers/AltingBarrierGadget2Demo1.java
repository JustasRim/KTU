
import org.jcsp.demos.util.*;
import org.jcsp.lang.*;
import org.jcsp.util.*;
import org.jcsp.plugNplay.*;

import java.awt.Color;

public class AltingBarrierGadget2Demo1 {

  public static void main (String[] argv) {

    // final int width = 20, depth = 15;
    //
    // final int span = 6;
    //
    // final int offInterval = 800, standbyInterval = 10000;     // milliseconds
    // final int playInterval = 10000                            // milliseconds

    final int width = Ask.Int ("\nwidth = ", 10, 30);
    final int depth = Ask.Int ("depth = ", 1, 30);

    final int nUnits = width*depth;
    
    final int span = Ask.Int ("span = ", 2, width);

    final int offInterval =
      Ask.Int ("off interval (millisecs) = ", 100, 10000);
    final int standbyInterval =
      Ask.Int ("standby interval (millisecs) = ", 100, 20000);
    final int playInterval =
      Ask.Int ("play interval (millisecs) = ", 1000, 1000000000);

    final Color offColour = Color.black, standbyColour = Color.lightGray;
    
    // make the buttons
    
    final One2OneChannel[][] click = new One2OneChannel[depth][];
    for (int i = 0; i < depth; i++) {
      click[i] = Channel.one2oneArray (width, new OverWriteOldestBuffer (1));
    }
    
    final One2OneChannel[][] configure = new One2OneChannel[depth][];
    for (int i = 0; i < depth; i++) {
      configure[i] = Channel.one2oneArray (width);
    }

    final FramedButtonGrid buttons =
      new FramedButtonGrid (
        "AltingBarrier: Gadget 2, Demo 1", depth, width,
	20 + (depth*50), width*50, Util.get2DInputArray(configure), Util.get2DOutputArray(click)
      );

    // construct nUnits barriers, each with span front-ends ...

    AltingBarrier[][] ab = new AltingBarrier[nUnits][];
    for (int i = 0; i < nUnits; i++) {
      ab[i] = AltingBarrier.create (span);
    }

    // re-arrange front-ends, ready for distribution to processes ...
    
    AltingBarrier[][]barrier = new AltingBarrier[nUnits][span];
    for (int i = 0; i < nUnits; i++) {
      for (int j = 0; j < span; j++) {
        barrier[i][j] = ab[(i + j) % nUnits][j];
      }
    }

    // make the track and the gadgets

    One2OneChannel[] forwards = Channel.one2oneArray (nUnits);
    One2OneChannel[] backwards = Channel.one2oneArray (nUnits);

    AltingBarrierGadget2[] gadgets = new AltingBarrierGadget2[nUnits];
    for (int i = 0; i < nUnits; i++) {
      gadgets[i] =
        new AltingBarrierGadget2 (
	  barrier[i],
	  forwards[i].in(), backwards[i].out(),
	  backwards[(i + 1)%nUnits].in(), forwards[(i + 1)%nUnits].out(),
	  click[i/width][i%width].in(), configure[i/width][i%width].out(),
	  offColour, standbyColour,
          offInterval, standbyInterval, playInterval
	);
    }

    // run everything

    new Parallel (
      new CSProcess[] {
        buttons, new Parallel (gadgets)
      }
    ).run ();

  }

}
